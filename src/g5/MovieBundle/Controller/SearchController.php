<?php
// /src/g5/MovieBundle/Controller/SearchController

namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use g5\MovieBundle\Form\Type\SearchType;
use g5\MovieBundle\Tmdb;

class SearchController extends Controller
{
    public function searchAction()
    {
        $form = $this->createForm(new SearchType());

        return $this->render('g5MovieBundle:Search:search.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function lookupAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        $form = $this->createForm(new SearchType());
        $form->bindRequest($this->getRequest());
        if ($form->isValid()) {
            $data = $form->getData();
            $tmdb = $this->get('g5.movie.tmdb_api');
            $res = $tmdb->searchMovie($data['search']);
            $imgUrl = $tmdb->getImageUrl(Tmdb::POSTER_SIZE_w154);
            $data['imgUrl'] = $imgUrl;
            $data['results'] = $res->results;
            return new JsonResponse($data);
        } else {
            return new JsonResponse('Not Found');
        }
    }

    public function loadmetaAction($tmdbid)
    {
        $tmdb = $this->get('g5.movie.tmdb_api');
        $res = $tmdb->getMovieData($tmdbid);

        return new JsonResponse($res);
    }
}
