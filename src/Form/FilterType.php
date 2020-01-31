<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('limit', IntegerType::class, [
                    'mapped' => false,
                    'required' => false
                ])
                ->add('offset', IntegerType::class, [
                    'mapped' => false,
                    'required' => false
                ])
                ->add('submit', SubmitType::class);
        ;
    }

    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'method' => 'GET'
        ));
    }
}