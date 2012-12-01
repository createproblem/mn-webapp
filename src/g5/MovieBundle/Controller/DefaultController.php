<?php
// src/g5/MovieBundle/Controller/DefaultController.php
namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use g5\MovieBundle\Form\Type\MovieType;
use g5\MovieBundle\Entity\Movie;
use g5\MovieBundle\Entity\Label;

class DefaultController extends Controller
{
    public function indexAction($page)
    {
        $t_page = $page - 1;
        $limit = 20;
        $offset = $t_page * $limit;
        
        $mr = $this->getDoctrine()->getManager()->getRepository('g5MovieBundle:Movie');
        $movies = $mr->findBy(array(), array('name' => 'ASC'), $limit, $offset);
        if (!$movies) {
            throw $this->createNotFoundException();
        }
        $count = $mr->getCount();
        $max = round($count / $limit);
        $mid = round($max / 2);
        $next = ($page != $max);
        
        return $this->render('g5MovieBundle:Default:index.html.twig', array(
            'movies'    => $movies,
            'max'       => $max,
            'mid'       => $mid,
            'current'   => $page,
            'next'      => $next
        ));
    }
    
    public function addAction()
    {
        
        
        if (!$this->getRequest()->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }
        
        if ($this->getRequest()->isMethod('POST')) {
            $query = trim($this->getRequest()->get('searchMovie'));
            $tmdb = $this->get('tmdb');
            $t_movies = $tmdb->searchMovie($query);
            $movies = array();
            if (isset($t_movies->total_results) && $t_movies->total_results > 0) {
                foreach ($t_movies->results as $t_movie) {
                    $movie = new Movie();
                    $movie->setName($t_movie->original_title);
                    $movie->setTmdbId($t_movie->id);
                    $movie->setRelease(new \DateTime($t_movie->release_date));
                    $movie->setBackdropPath($tmdb->getImageUrl($t_movie->poster_path));
                    $movieData = $tmdb->getMovieData($movie->getTmdbId());
                    $movie->setOverview($movieData->overview);
                    $movies[] = $movie;
                }
                
                $form = $this->createForm(new MovieType());
                $serializer = new Serializer(array(new GetSetMethodNormalizer()), array(
                    'json'  => new JsonEncoder()
                ));
                
                $jsonData = $serializer->serialize($movies, 'json');
                //var_dump($jsonData);
                return $this->render('g5MovieBundle:Default:searchResult.html.twig', array(
                    'movies'    => $movies,
                    'results'   => count($movies),
                    'form'      => $form->createView(),
                    'jsont'      => $jsonData
                ));
            }
            return new Response('Bad');
        }
        
        return $this->render('g5MovieBundle:Default:add.html.twig');
    }
    
    public function importAction()
    {
        /*$em = $this->getDoctrine()->getManager();
        $label = $em->getRepository('g5MovieBundle:label')->find(1);
        $movie = $em->getRepository('g5MovieBundle:Movie')->find(23);
        $movie->addLabel($label);
        $em->persist($movie);
        $em->flush();
        die();*/
         \set_time_limit(0); 
        $fh = fopen('horror.txt', 'r');
        $files = array();
        $movienames = array();
        while (($buffer = fgets($fh, 1024)) !== false) {
            $files[] = $buffer;
        }
        fclose($fh);
        $ignore = pathinfo($files[0], PATHINFO_DIRNAME);
        $data = array();

        for ($i = 0; $i < count($files); $i++) {
            $tpath = str_replace($ignore, '', $files[$i]);
            $tpath = str_replace('\\', '/', $tpath);
            $tpath = substr($tpath, 1);
                 
            $filename = pathinfo($tpath, PATHINFO_FILENAME);
            $ext = pathinfo($tpath, PATHINFO_EXTENSION);
            if (empty($ext)) {
                continue;
            }
            $filenameParts = explode('-', $filename);
            $sort = explode('/', $tpath);
            if (count($sort) > 2) {
                $cat = $sort[0];
                $col = $sort[1];
            } else {
                $cat = $sort[0];
                $col = null;
            }
            $moviename = trim($filenameParts[0]) . ' ' . trim($filenameParts[1]);
            $data[$moviename] = array(
                'cat'   => $cat,
                'col'   => $col,
                'year'  => trim($filenameParts[1]),
                'name'  => trim($filenameParts[0])
            );
        }
       
        $em = $this->getDoctrine()->getManager();
        $tmdb = $this->get('tmdb');
        $categories = array();
        $storeData = array();
        $categoryObjects = array();
        foreach ($data as $name => $tdata) {
            $categories[] = $tdata['cat'];
            //$col[] = $tdata['col'];
            $movies = $tmdb->searchMovie($tdata['name'], $tdata['year']);
            echo $tmdb->getLastRequestUrl() . '<br />';
            if (!isset($movies->total_results) || $movies->total_results !== 1) {
                continue;
            }
            $movie = new Movie();
            $movie->setName($movies->results[0]->original_title);
            $movie->setTmdbId($movies->results[0]->id);
            $movie->setRelease(new \DateTime($movies->results[0]->release_date));
            $movie->setBackdropPath($tmdb->getImageUrl($movies->results[0]->poster_path));
            $movieData = $tmdb->getMovieData($movie->getTmdbId());
            $movie->setOverview($movieData->overview);
            $t_movie = $em->getRepository('g5MovieBundle:Movie')->findBy(array(
                'tmdb_id' => $movie->getTmdbId()
            ));
            if (!$t_movie) {
                $storeData[] = $movie;
            }
        }
        $cat = new Label();
        $cat->setName('HORROR');
        $em->persist($cat);
        //$em->flush();
        foreach ($storeData as $m) {
            $m->addLabel($cat);
            $em->persist($m);
        }
        $em->flush();
        return new Response('OK');
    }
    
    public function saveTmdbAction()
    {
        $form = $this->createForm(new MovieType());
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $tmdb = $this->get('tmdb');
            $t_movie = $tmdb->getMovieData($form->get('tmdb_id')->getData());
            //var_dump($form->get('tmdb_id')->getData());
            $movie = Movie::generateByTmdbMovie($t_movie);
            $tm = $em->getRepository('g5MovieBundle:Movie')->findBy(array(
                'tmdb_id' => $movie->getTmdbId()
            ));
            if ($tm) {
                return $this->render('::alert.html.twig', array(
                    'alert' => array(
                        'type'  => 'alert-info',
                        'head' => 'Info!',
                        'msg'   => 'Movie already exists.'
                    )
                ));
            }
            $em->persist($movie);
            $em->flush();
            return $this->render('::alert.html.twig', array(
                'alert' => array(
                    'type'  => 'alert-success',
                    'head' => 'Well done!',
                    'msg'   => 'Movie saved successfully.'
                )
            ));
        } else {
            return $this->render('::alert.html.twig', array(
                'alert' => array(
                    'type'  => 'alert-error',
                    'head' => 'Bad Request!',
                    'msg'   => 'This Request was not allowed.'
                )
            ));
        }
    }
}
