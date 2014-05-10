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
use g5\HomeBundle\Components\Normalizer;

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
            $raw = explode(',', $link->getName());
            $labelsRaw = array();

            foreach ($raw as $name) {
                $nameNorm = $this->normalizer->normalizeUtf8String($name);
                $labelsRaw[$nameNorm] = $name;
            }

            $movie = $this->movieManager->find($link->getMovieId(), $user);

            if (!$movie) {
                $this->errors['error'] = 'Movie not found.';
                return false;
            }

            // find existing labels
            $labelsExist = $this->labelManager->repository->findByNamesNorm(array_keys($labelsRaw), $user);
            foreach ($labelsExist as $label) {
                if (isset($labelsRaw[$label->getNameNorm()])) {
                    unset($labelsRaw[$label->getNameNorm()]);
                }
            }

            // delete labels
            $labelsDel = array_diff($movie->getLabels(), $labelsExist);

            foreach ($labelsDel as $label) {
                $movie->removeLabel($label);
            }

            // link new labels
            foreach ($labelsExist as $label) {
                if (!$movie->hasLabel($label)) {
                    $movie->addLabel($label);
                }
            }

            // add new labels
            foreach ($labelsRaw as $nameNorm => $name) {
                if (strlen($nameNorm) === 0)
                    continue;
                $label = $this->labelManager->createLabel();
                $label->setName($name);
                $label->setNameNorm($nameNorm);
                $label->setUser($user);
                $this->labelManager->updateLabel($label);
                $movie->addLabel($label);
            }

            $this->movieManager->updateMovie($movie);

            return $movie->getLabels();
        }
    }
}
