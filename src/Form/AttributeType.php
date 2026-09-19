<?php

namespace App\Form;

use App\Entity\AttributeCategory;
use App\Entity\AttributeCv;
use App\Enum\AttributeTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Enter attribute name'),
                ],
            ])
            ->add('description', TextareaType::class)
            ->add('type', EnumType::class, [
                'class' => AttributeTypeEnum::class,
                'choice_label' => 'value',
                'label' => 'Data type',
            ])
            ->add('category', EntityType::class, [
                'class' => AttributeCategory::class,
                'choice_label' => 'name',
            ])
            ->add('oneOfManies', CollectionType::class, [
                'entry_type' => OneOfManyType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
                'error_bubbling' => false,
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AttributeCv::class,
            'allow_extra_fields' => true,
        ]);
    }
}
