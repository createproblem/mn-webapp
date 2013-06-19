<?php
// /src/g5/MovieBundle/Controller/MovieController

namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use g5\MovieBundle\Form\Type\SearchType;
use g5\MovieBundle\Entity\Movie;
use g5\MovieBundle\Tmdb;

class MovieController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();

        $movies = $user->getMovies();
        // print_r($movies);

        return $this->render('g5MovieBundle:Movie:index.html.twig', array(
            'movies' => $movies,
        ));
    }

    /**
     * Adds a Movie
     */
    public function addAction($tmdbId)
    {
        $em = $this->getDoctrine()->getManager();
        $moviemanager = $this->get('g5.movie.movie_manager');
        $validator = $this->get('validator');
        $user = $this->getUser();

        $movie = $moviemanager->createMovie($tmdbId);
        $movie->setUserId($user->getId());


        $errors = $validator->validate($movie);
        if (count($errors) > 0) {
            $messages = array();
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            return new JsonResponse($messages);
        }
        $movie->setUser($user);
        $em->persist($movie);
        $em->flush();

        return new JsonResponse($movie->getTmdbid());
    }

    /**
     * Search for Movie in the tmdb db
     */
    public function searchAction()
    {
        $tmdb = $this->get('g5.movie.tmdb_api');
        $request = $this->getRequest();
        $form = $this->createForm(new SearchType());

        // if GET return search template
        if (!$request->isMethod('POST')) {

            return $this->render('g5MovieBundle:Movie:search.html.twig', array(
                'form' => $form->createView(),
                'results' => array(),
                'imgUrl' => $tmdb->getImageUrl(Tmdb::POSTER_SIZE_w185),
            ));
        }

        $form->bind($request);
        if ($form->isValid()) {
            $formData = $form->getData();
            $res = $tmdb->searchMovie($formData['search']);

            return $this->render('g5MovieBundle:Movie:search.html.twig', array(
                'form' => $form->createView(),
                'results' => $res->results,
                'imgUrl' => $tmdb->getImageUrl(Tmdb::POSTER_SIZE_w185),
            ));
        } else {
            throw $this->createNotFoundException('Empty search not allowed');
        }
    }

    /**
     * Load Movie details
     *
     * @param  int $tmdbId
     */
    public function loadmetaAction($tmdbId)
    {
        $tmdb = $this->get('g5.movie.tmdb_api');
        $res = $tmdb->getMovieData($tmdbId);

        return new JsonResponse($res);
    }
}
