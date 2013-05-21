<?php
// src/g5/MovieBundle/Form/Type/MovieType.php
namespace g5\MovieBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', 'text', array(
            'label' => 'Search Movie',
            'attr' => array('placeholder' => 'Movie Title'),
        ));
    }

    // public function setDefaultOptions(OptionsResolverInterface $resolver)
    // {
    //     $resolver->setDefaults(array(
    //         'data_class' => 'g5\MovieBundle\Entity\Movie'
    //     ));
    // }

    public function getName()
    {
        return 'movie';
    }
}

?>
