<?php

namespace App\Form;

use App\Entity\AttributeCv;
use App\Entity\User;
use App\Entity\UserAttribute;
use App\Enum\AttributeTypeEnum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserAttributeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $userAttribute = $event->getData();
            $form = $event->getForm();

            $attribute = $userAttribute->getAttribute();
            $type = $attribute->getType();

            switch ($type) {
                case AttributeTypeEnum::StringType:
                    $form->add('valString', TextType::class, [
                        'label' => $attribute->getName(),
                        'constraints' => [new NotBlank()],
                    ]);
                    break;
                case AttributeTypeEnum::TextType:
                    $form->add('valText', TextareaType::class, [
                        'label' => $attribute->getName(),
                        'constraints' => [new NotBlank()],
                    ]);
                    break;
                case AttributeTypeEnum::ImageType:
                    $form->add('imageFile', FileType::class, [
                        'label' => $attribute->getName(),
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new Image(
                                maxSize: '5M',
                                mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                            ),
                        ],
                    ]);
                    break;
                case AttributeTypeEnum::NumericType:
                    $form->add('valNumber', NumberType::class, [
                        'html5' => true,
                        'label' => $attribute->getName(),
                        'constraints' => [new NotBlank()],
                    ]);
                    break;
                case AttributeTypeEnum::DateType:
                    $form->add('valDate', DateType::class, [
                        'label' => $attribute->getName(),
                        'constraints' => [new NotBlank()],
                    ]);
                    break;
                case AttributeTypeEnum::PeriodType:
                    $form->add('valDate', DateType::class, [
                        'label' => $attribute->getName(),
                        'constraints' => [new NotBlank()],
                    ]);
                    $form->add('valDatePeriod', DateType::class, [
                        'label' => $attribute->getName(),
                        'constraints' => [new NotBlank()],
                    ]);
                    break;
                case AttributeTypeEnum::BoolType:
                    $form->add('valBool', CheckboxType::class, [
                        'label' => $attribute->getName(),
                    ]);
                    break;
                case AttributeTypeEnum::OneOfMany:
                    $choices = [];

                    foreach ($attribute->getOneOfManies() as $option) {
                        $choices[$option->getValue()] = $option->getValue();
                    }

                    $form->add('valDropdown', ChoiceType::class, [
                        'label' => $attribute->getName(),
                        'choices' => $choices,
                        'placeholder' => 'Select an option',
                        'constraints' => [new NotBlank()],
                    ]);
                    break;
            }
        });

        // $builder
        //     ->add('valString')
        //     ->add('valText')
        //     ->add('valDate', null, [
        //         'widget' => 'single_text'
        //     ])
        //     ->add('valDatePeriod', null, [
        //         'widget' => 'single_text'
        //     ])
        //     ->add('valBool')
        //     ->add('valDropdown')
        //     ->add('valImage')
        //     ->add('valNumber')
        //     ->add('attribute', EntityType::class, [
        //         'class' => AttributeCv::class,
        //         'choice_label' => 'id',
        //     ])
        //     ->add('user', EntityType::class, [
        //         'class' => User::class,
        //         'choice_label' => 'id',
        //     ])
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAttribute::class,
        ]);
    }
}
