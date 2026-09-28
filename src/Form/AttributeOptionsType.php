<?php

namespace App\Form;

use App\Entity\AttributeCv;
use App\Entity\User;
use App\Entity\UserAttribute;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('valString')
            ->add('valText')
            ->add('valDate', null, [
                'widget' => 'single_text'
            ])
            ->add('valDatePeriod', null, [
                'widget' => 'single_text'
            ])
            ->add('valBool')
            ->add('valDropdown')
            ->add('valImage')
            ->add('valNumber')
            ->add('attribute', EntityType::class, [
                'class' => AttributeCv::class,
                'choice_label' => 'id',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAttribute::class,
        ]);
    }
}
