<?php

namespace App\Form;

use App\Entity\AttributeCv;
use App\Entity\Cv;
use App\Entity\Position;
use App\Entity\User;
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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class CvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['position']->getAttributes() as $attribute) {
            $name = $attribute->getId();
            $prefill = $options['prefill'][$attribute->getId()] ?? null;


            switch ($attribute->getType()) {
                case AttributeTypeEnum::StringType:
                    $builder->add($name, TextType::class, [
                        'label' => $attribute->getName(),
                        'data'     => $prefill->getValString() ?? null,
                    ]);
                    break;
                case AttributeTypeEnum::TextType:
                    $builder->add($name, TextareaType::class, [
                        'label' => $attribute->getName(),
                        'data'     => $prefill->getValText() ?? null,
                    ]);
                    break;
                case AttributeTypeEnum::ImageType:
                    $builder->add($name, FileType::class, [
                        'label' => $attribute->getName(),
                        'mapped' => false,
                        'required' => false,
                        'data' => $prefill->getValImage() ?? null,
                        'constraints' => [
                            new Image(
                                maxSize: '5M',
                                mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                            ),
                        ],
                    ]);
                    break;
                case AttributeTypeEnum::NumericType:
                    $builder->add($name, NumberType::class, [
                        'html5' => true,
                        'label' => $attribute->getName(),
                        'data'     => $prefill->getValNumber() ?? null,
                    ]);
                    break;
                case AttributeTypeEnum::DateType:
                    $builder->add($name, DateType::class, [
                        'label' => $attribute->getName(),
                        'data'     => $prefill->getValDate() ?? null,
                    ]);
                    break;
                case AttributeTypeEnum::PeriodType:
                    $builder->add($name, DatePeriodType::class, [
                        'label' => $attribute->getName(),
                        'required' => false,
                        'data' => ['from' => $prefill->getValDate() ?? null, 'to' => $prefill->getValDatePeriod() ?? null],
                    ]);
                    break;
                case AttributeTypeEnum::BoolType:
                    $builder->add('valBool', CheckboxType::class, [
                        'label' => $attribute->getName(),
                        'data'     => $prefill->isValBool() ?? null,
                    ]);
                    break;
                case AttributeTypeEnum::OneOfMany:
                    $choices = [];

                    foreach ($attribute->getOneOfManies() as $option) {
                        $choices[$option->getValue()] = $option->getValue();
                    }

                    $builder->add('valDropdown', ChoiceType::class, [
                        'label' => $attribute->getName(),
                        'data'     => $prefill->getValDropdown() ?? null,
                        'choices' => $choices,
                        'placeholder' => 'Select an option',
                    ]);
                    break;
            }
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['position', 'prefill']);
        $resolver->setAllowedTypes('position', Position::class);
        $resolver->setAllowedTypes('prefill', []);

        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
