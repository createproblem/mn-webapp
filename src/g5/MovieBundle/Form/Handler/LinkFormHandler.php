<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use g5\MovieBundle\Form\Model\Link;
use g5\MovieBundle\Util\LabelManager;
use g5\MovieBundle\Util\MovieManager;
use g5\AccountBundle\Entity\User;
use g5\ToolsBundle\Util\Normalizer;

class LinkFormHandler
{
    /**
     * @var LabelManager
     */
    protected $labelManager;

    /**
     * @var MovieManager
     */
    protected $movieManager;

    /**
     * @var Normalizer
     */
    protected $normalizer;

    /**
     * @var array
     */
    private $errors = array();

    /**
     * Constructor.
     *
     * @param LabelManager $labelManager
     * @param MovieManager $movieManager
     * @param Normalizer   $normalizer
     */
    public function __construct(LabelManager $labelManager, MovieManager $movieManager, Normalizer $normalizer)
    {
        $this->labelManager = $labelManager;
        $this->movieManager = $movieManager;
        $this->normalizer = $normalizer;
    }

    /**
     * Handle the Form
     *
     * @param Form  $form
     * @param User  $user
     *
     * @return Label|boolean
     */
    public function process(Form $form, User $user)
    {
        if ($form->isValid()) {
            $link = $form->getData();
            $movie = $this->movieManager->find($link->getMovieId(), $user);

            if (!$movie) {
                return false;
            }

            $labelNameNorm = $this->normalizer->normalizeUtf8String($link->getName());
            $label = $this->labelManager->findLabelBy(array('user' => $user, 'name_norm' => $labelNameNorm));

            if (!$label) {
                $label = $this->labelManager->createLabel();
                $label->setName($link->getName());
                $label->setNameNorm($labelNameNorm);
                $label->setUser($user);
                $this->labelManager->updateLabel($label);

                $movie->addLabel($label);
            } elseif ($movie->hasLabel($label)) {
                $this->errors['error'] = 'Label already assigned.';
                return false;
            } else {
                $movie->addLabel($label);
            }

            $this->movieManager->updateMovie($movie);
            return $label;
        }
        $this->errors = $form->getErrors();
        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
