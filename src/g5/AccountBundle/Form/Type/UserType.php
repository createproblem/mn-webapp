<?php
// src/g5/AccountBundle/Form/Type/UserType.php

namespace g5\AccountBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form for user creation
 */
class UserType extends AbstractType
{
    /**
     * Build the form to create users
     *
     * @param  FormBuilderInterface $builder [description]
     * @param  array       $options [description]
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('email', 'email');
        $builder->add('password', 'repeated', array(
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
