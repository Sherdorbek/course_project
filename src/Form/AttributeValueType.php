<?php

namespace App\Form;

use App\Entity\AttributeValue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string')
            ->add('text')
            ->add('image')
            ->add('num')
            ->add('date')
            ->add('period')
            ->add('bool')
            ->add('one_of_many')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AttributeValue::class,
        ]);
    }
}
