<?php
// src/g5/MovieBundle/Form/Type/SearchType.php
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
            'attr' => array('placeholder' => 'Movie Title'),
            'required' => true,
        ));

        $searchFieldValidator = function(FormEvent $event) {
            $form = $event->getForm();
            $searchField = $form->get('search')->getData();
            if (empty($searchField)) {
                $form['search']->addError(new FormError('Search must not be empty'));
            }
        };

        $builder->addEventListener(FormEvents::POST_BIND, $searchFieldValidator);
    }

    public function getName()
    {
        return 'g5_movie_search';
    }
}

?>
