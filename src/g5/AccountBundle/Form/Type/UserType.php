<?php
// src/g5/AccountBundle/Form/Type/UserType.php

namespace g5\AccountBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

/**
 * Form for user creation
 */
class UserType extends AbstractType
{
    /**
     * Build the form to create users
     *
     * @param  FormBuilder $builder [description]
     * @param  array       $options [description]
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('email', 'email');
        $builder->add('plainPassword', 'repeated', array(
            'first_name'    => 'password',
            'second_name'   => 'confirm',
            'type'          => 'password'
        ));
    }

    /**
     * Returns the storage class for users
     *
     * @param  array  $options [description]
     *
     * @return array          [description]
     */
    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'g5\AccountBundle\Document\User');
    }

    /**
     * Returns the identifier name
     *
     * @return string [description]
     */
    public function getName()
    {
        return 'user';
    }
}
