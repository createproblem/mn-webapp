<?php
// src/g5/MovieBundle/Form/Type/SearchType.php
namespace g5\MovieBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', 'text', array(
            'attr' => array('placeholder' => 'Movie Title'),
            'required' => true,
        ));
    }

    public function getName()
    {
        return 'g5_movie_search';
    }
}

?>
