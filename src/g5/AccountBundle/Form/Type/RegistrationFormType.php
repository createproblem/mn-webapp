<?php
// src/g5/AccountBundle/Form/Type/RegistrationFormType.php

namespace g5\AccountBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', null, array(
            'attr' => array(
                'class' => 'validate[required]',
            )
        ));

        $builder->add('email', null, array(
            'attr' => array('class' => 'validate[required,custom[email]]')
        ));

        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'first_name' => 'password',
            'second_name' => 'confirm_password',
            'attr' => array('class' => 'validate[required]')
        ));

        $builder->add('termsOfService', 'checkbox', array(
            'label' => 'I agree to the terms and service',
        ));
    }

    public function getName()
    {
        return 'g5_registration_form_type';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'g5\AccountBundle\Document\User',
            'intention'  => 'registration',
        ));
    }
}
