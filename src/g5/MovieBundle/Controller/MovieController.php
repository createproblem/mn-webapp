<?php
// /src/g5/MovieBundle/Controller/MovieController

namespace g5\MovieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use g5\MovieBundle\Form\Type\MovieType;
use g5\MovieBundle\Document\Movie;

class MovieController extends Controller
{
    public function addAction()
    {
        $request = $this->getRequest();
        $form = $this->createForm(new MovieType());

        if ($request->isMethod('POST')) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $dm = $this->get('doctrine_mongodb')->getManager();
                $movie = new Movie();
                $movie->setName($form->getAttribute('name'));
                $movie->setMeta(array('testVar' => 'testVal'));
                $dm->persist($movie);
                $dm->flush();
            }
        }

        return $this->render('g5MovieBundle:Movie:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
