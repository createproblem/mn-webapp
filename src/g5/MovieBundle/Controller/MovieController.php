<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use g5\MovieBundle\Form\Type\SearchType;
use g5\MovieBundle\Entity\Movie;

class MovieController extends Controller
{
    /**
     * Shows the movie detail view. The movie with the given id will be loaded
     * with the user as criteria. Only a movie owned by the current user can be displayed.
     * If the movie cannot be loaded a 404 is thrown.
     *
     * @param  int      $id     The Movie Id
     *
     * @throws NotFoundException If the movie cannot be loaded
     *
     * @return Response
     */
    public function indexAction($id)
    {
        $mm = $this->get('g5_movie.movie_manager');
        $tmdbApi = $this->get('g5_tools.tmdb.api');
        $user = $this->getUser();

        $movie = $mm->loadMovieById($id, $user);

        if (!$movie) {
            throw $this->createNotFoundException('The requests movie was not found.');
        }

        return $this->render('g5MovieBundle:Movie:index.html.twig', array(
            'movie' => $movie,
            'imgUrl_w185' => $tmdbApi->getImageUrl('w185'),
            'imgUrl_w300' => $tmdbApi->getImageUrl('w300'),
            'imgUrl_w780' => $tmdbApi->getImageUrl('w780'),
            'imgUrl_w342' => $tmdbApi->getImageUrl('w342'),
        ));
    }

    /**
     * List all movies the user owns
     *
     * @param  int $page
     *
     * @return Response
     */
    public function listAction($page)
    {
        $user = $this->getUser();
        $mm = $this->get('g5_movie.movie_manager');
        $movieCount = count($user->getMovies());

        $limit = 20;
        $offset = ($page - 1) * $limit;
        $lastPage = ceil($movieCount / $limit);

        $movies = $mm->findMoviesBy(array('user' => $user), array('title' => 'ASC'), $limit, $offset);
        $tmdbApi = $this->get('g5_tools.tmdb.api');

        $pagination = array(
            'page' => $page,
            'page_items' => $limit,
            'item_count' => $movieCount,
            'url' => array(
                'route' => 'g5_movie_list',
                'params' => array(
                    ':page' => 'page',
                ),
            ),
        );

        return $this->render('g5MovieBundle:Movie:list.html.twig', array(
            'movies' => $movies,
            'imgUrl' => $tmdbApi->getImageUrl('w185'),
            'pagination' => $pagination,
        ));
    }

    /**
     * Show all favorite movies
     *
     * @param  int $page [description]
     *
     * @return Response
     */
    public function favoriteAction($page)
    {
        $user = $this->getUser();
        $mm = $this->get('g5_movie.movie_manager');
        $movies = $mm->loadMoviesByFavorite($user);
        $movieCount = count($movies);

        $limit = 20;
        $offset = ($page - 1) * $limit;
        $lastPage = ceil($movieCount / $limit);

        $movies = $mm->loadMoviesByFavorite($user, true, $limit, $offset);
        $tmdbApi = $this->get('g5_tools.tmdb.api');

        $pagination = array(
            'page' => $page,
            'page_items' => $limit,
            'item_count' => $movieCount,
            'url' => array(
                'route' => 'g5_movie_list',
                'params' => array(
                    ':page' => 'page',
                ),
            ),
        );

        return $this->render('g5MovieBundle:Movie:list.html.twig', array(
            'movies' => $movies,
            'imgUrl' => $tmdbApi->getImageUrl('w185'),
            'pagination' => $pagination,
        ));
    }

    public function unlabeledAction($page)
    {
        $user = $this->getUser();
        $mm = $this->get('g5_movie.movie_manager');
        $movieCount = count($mm->findMoviesWithoutLabel($user));

        $limit = 20;
        $offset = ($page - 1) * $limit;
        $lastPage = ceil($movieCount / $limit);

        $movies = $mm->findMoviesWithoutLabel($user, $limit, $offset);

        $tmdbApi = $this->get('g5_tools.tmdb.api');

        $pagination = array(
            'page' => $page,
            'page_items' => $limit,
            'item_count' => $movieCount,
            'url' => array(
                'route' => 'g5_movie_unlabeled',
                'params' => array(
                    ':page' => 'page',
                ),
            ),
        );

        return $this->render('g5MovieBundle:Movie:list.html.twig', array(
            'movies' => $movies,
            'imgUrl' => $tmdbApi->getImageUrl('w185'),
            'pagination' => $pagination,
        ));
    }

    public function newAction(Request $request)
    {
        $form = $this->createForm(new SearchType());

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $query = $form->get('search')->getData();
                $api = $this->get('g5_tmdb.api.default');
                var_dump($api->getSearchMovie(array('query' => $query)));
                $api->getSearchMovie(array('query' => 'Fight Club'));
                var_dump($query);
            }
        }

        return $this->render('g5MovieBundle:Movie:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function searchTmdbAction()
    {
        $request = $this->getRequest();

        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('Wrong Request Type.');
        }

        $form = $this->createForm(new SearchType());
        $form->bind($request);

        if ($form->isValid()) {
            $tmdbApi = $this->get('g5_tools.tmdb.api');

            $query = array(
                'query' => $form->get('search')->getData(),
            );

            $result = $tmdbApi->searchMovie($query);
            $movies = $result['results'];

            return $this->render('g5MovieBundle:Movie:searchResult.html.twig', array(
                'movies' => $movies,
                'imgUrl' => $tmdbApi->getImageUrl('w185'),
            ));
        }

        throw $this->createNotFoundException('Api access denied.');
    }

    /**
     * Adds a Movie
     */
    public function addAction()
    {
        $mm = $this->get('g5_movie.movie_manager');
        $validator = $this->get('validator');
        $user = $this->getUser();
        $tmdbId = $this->getRequest()->get('tmdbId');

        $movie = $mm->createMovieFromTmdb($tmdbId);
        $movie->setUser($user);

        $errors = $validator->validate($movie);
        if (count($errors) > 0) {
            $messages = array();
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            return new JsonResponse($messages);
        }

        $mm->updateMovie($movie);

        return new JsonResponse($movie->getTmdbId());
    }

    public function loadTmdbAction()
    {
        $tmdbApi = $this->get('g5_tools.tmdb.api');
        $request = $this->getRequest();
        $result = $tmdbApi->getMovie($request->get('tmdbId'));

        $response = new JsonResponse();
        $response->setData($result);

        return $response;
    }
}
