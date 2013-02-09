<?php
// src/g5/MovieBundle/Form/Type/LabelType.php
namespace g5\MovieBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('required'  => true));
        $builder->add('color', 'choice', array(
            'choices'   => array(
                'label-success'     => 'Green',
                'label-warning'     => 'Orange',
                'label-important'   => 'Red',
                'label-info'        => 'Blue',
                'label-inverse'     => 'Black'   
             ),
            'required'  => true
        ));
    }
    
    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'g5\MovieBundle\Document\Labelm');
    }
    
    public function getName()
    {
        return 'labelm';
    }
}
?>
