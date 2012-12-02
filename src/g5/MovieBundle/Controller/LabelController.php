<?php
// src/g5/MovieBundle/Controller/LabelController.php
namespace g5\MovieBundle\Controller;
ini_set("error_reporting", E_ALL & ~E_DEPRECATED);

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use g5\MovieBundle\Form\Type\LabelType;
//use g5\MovieBundle\Document\Labelm;

class LabelController extends Controller
{
    public function indexAction()
    {
        return $this->render('g5MovieBundle:Label:index.html.twig');
    }
    
    public function addAction($id)
    {
        $form = $this->createForm(new LabelType());
        
        if ($this->getRequest()->isMethod('post')) {
            $form->bind($this->getRequest());
            if ($form->isValid()) {
                $dm = $this->get('doctrine_mongodb')->getManager();
                // workaround to avoid wrong form handling
                $newLabel = $form->getData();
                $newLabel->setColor($form->get('color')->getViewData());
                $exiLabel = $dm->getRepository('g5MovieBundle:Labelm')->findOneByName($newLabel->getName());
                if($exiLabel) {
                    $exiLabel->setColor($newLabel->getColor());
                    $label = $exiLabel;
                } else {
                    $label = $newLabel;
                }
                $dm->persist($label);
                $movieObjectId = (int)$this->getRequest()->request->get('movieObjectId');
                $movie = $dm->getRepository('g5MovieBundle:Moviem')->findByTmdbId($movieObjectId);
                if ($movie) {
                    $movie->addLabels($label);
                    var_dump('linked');
                    //$dm->persist($movie);
                    $label->addMovies($movie);
                }
                $dm->flush();
                return new Response('OK');
            }
            return new Response('FAIL');
        }
            
        return $this->render('g5MovieBundle:Label:add.html.twig', array(
            'form' => $form->createView(),
            'movieObjectId' => $id
        ));
    }
}