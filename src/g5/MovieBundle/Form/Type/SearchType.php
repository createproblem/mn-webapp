<?php

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace g5\MovieBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', 'text', array(
            'attr' => array(
                'placeholder' => 'Movie Title',
                'class' => 'form-control',
            ),
            'required' => true,
            'mapped' => false
        ));

        // $searchFieldValidator = function(FormEvent $event) {
        //     $form = $event->getForm();
        //     $searchField = $form->get('search')->getData();
        //     if (strlen($searchField) === 0) {
        //         $form['search']->addError(new FormError('Search must not be empty'));
        //     }
        // };

        // $builder->addEventListener(FormEvents::POST_BIND, $searchFieldValidator);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'g5_movie_search',
        ));
    }

    public function getName()
    {
        return 'g5_movie_search';
    }
}

?>
