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
     * @var Request
     */
    protected $request;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var LabelManager
     */
    protected $lm;

    /**
     * @var MovieManager
     */
    protected $mm;

    /**
     * @var Normalizer
     */
    protected $normalizer;

    /**
     * Initialize the handler
     *
     * @param Request $request
     * @param Form    $form
     */
    public function __construct(Request $request, Form $form, LabelManager $lm, MovieManager $mm, Normalizer $normalizer)
    {
        $this->request = $request;
        $this->form = $form;
        $this->lm = $lm;
        $this->mm = $mm;
        $this->normalizer = $normalizer;
    }

    /**
     * Handle the Link
     *
     * @param  Link   $link [description]
     *
     * @return [type]       [description]
     */
    public function process(Link $link, User $user)
    {
        $this->form->setData($link);
        $this->form->bind($this->request);

        if ($this->form->isValid()) {
            $movie = $this->mm->loadMovieById($link->getMovieId(), $user);
            if (!$movie) {
                return false;
            }
            $labelNameNorm = $this->normalizer->normalizeUtf8String($link->getName());
            $label = $this->lm->findLabelBy(array('user' => $user, 'name_norm' => $labelNameNorm));
            if (!$label) {
                $label = $this->lm->createLabel();
                $label->setName($link->getName());
                $label->setNameNorm($labelNameNorm);
                $label->setUser($user);
                $this->lm->updateLabel($label);

                $movie->addLabel($label);
            } elseif ($movie->hasLabel($label)) {
                return false;
            } else {
                $movie->addLabel($label);
            }

            $this->mm->updateMovie($movie);
            return $label;
        }

        return false;
    }

}
