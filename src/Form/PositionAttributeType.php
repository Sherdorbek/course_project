<?php

namespace App\Form;

use App\Entity\AttributeCv;
use App\Entity\Position;
use App\Entity\PositionAttr;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rowOrder',NumberType::class)
            ->add('position', EntityType::class, [
                'class' => Position::class,
                'choice_label' => 'id',
            ])
            ->add('attribute', EntityType::class, [
                'class' => AttributeCv::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PositionAttr::class,
        ]);
    }
}
