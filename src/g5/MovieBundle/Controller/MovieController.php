<?php
// /src/g5/MovieBundle/Controller/MovieController

namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use g5\MovieBundle\Form\Type\SearchType;
use g5\MovieBundle\Document\Movie;
use g5\MovieBundle\Tmdb;

class MovieController extends Controller
{
    /**
     * Adds a Movie
     */
    public function addAction()
    {
        $moviemanager = $this->get('g5.movie.movie_manager');

        $movie = $moviemanager->createMovie(550);
        $movie->setTmdbid(550);

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
            return $this->createNotFoundException();
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
