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

use g5\MovieBundle\Form\DataTransformer\LabelNameNormTransformer;

class LinkType extends AbstractType
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
            'mapped' => true,
        ));

        $builder->add('movie_id', 'hidden', array(
            'required' => true,
            'mapped' => true,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'g5\MovieBundle\Form\Model\Link',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'intention'       => 'g5_movie_link',
        ));
    }

    public function getName()
    {
        return 'link';
    }
}
