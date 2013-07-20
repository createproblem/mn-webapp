<?php
// /src/g5/MovieBundle/Controller/MovieController

namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use g5\MovieBundle\Form\Type\SearchType;
use g5\MovieBundle\Entity\Movie;

class MovieController extends Controller
{
    public function indexAction()
    {
        // throw $this->createNotFoundException('asd');
        $user = $this->getUser();
        $movies = $user->getMovies();
        $tmdbApi = $this->get('g5_tools.tmdb.api');

        $form = $this->createForm('label');

        return $this->render('g5MovieBundle:Movie:index.html.twig', array(
            'movies' => $movies,
            'imgUrl' => $tmdbApi->getImageUrl('w185'),
            'labelForm' => $form->createView(),
        ));
    }

    public function newAction()
    {
        $form = $this->createForm(new SearchType());

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
        $em = $this->getDoctrine()->getManager();
        $moviemanager = $this->get('g5_movie.movie_manager');
        $validator = $this->get('validator');
        $user = $this->getUser();
        $tmdbId = $this->getRequest()->get('tmdbId');

        $movie = $moviemanager->createMovieFromTmdb($tmdbId);

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
