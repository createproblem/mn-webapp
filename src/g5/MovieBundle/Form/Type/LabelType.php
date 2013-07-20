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

class LabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
            'attr' => array(
                'data-provide' => 'typeahead',
                'autocomplete' => 'off',
                'class' => 'span9',
            ),
            'required' => true,
        ));

        $builder->add('movie_id', 'hidden', array(
            'mapped' => false,
            'required' => false,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'g5\MovieBundle\Entity\Label',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'g5_movie_label',
        ));
    }

    public function getName()
    {
        return 'label';
    }
}
