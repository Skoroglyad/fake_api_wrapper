<?php


namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

class ExtendedFilterType extends FilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
                ->add('authorId', IntegerType::class, [
                    'mapped' => false,
                    'required' => false
                ])
        ;
    }

}