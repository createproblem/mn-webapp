<?php
// src/g5/MovieBundle/Form/Type/LabelType.php
namespace g5\MovieBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('color', 'checkbox', array('value' => 'label-warning', 'required' => true));
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
