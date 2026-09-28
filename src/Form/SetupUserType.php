<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserAttribute;
use App\Enum\AttributeTypeEnum;
use App\Repository\AttributeCvRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SetupUserType extends AbstractType
{
    public function __construct(private AttributeCvRepository $attrRepo) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $mandatoryAttrs = $this->attrRepo->findBy(['isRemovable' => false], ['id' => 'ASC']);

        foreach ($mandatoryAttrs as $attribute) {
            $type = match ($attribute->getType()) {
                AttributeTypeEnum::StringType => TextType::class,
                AttributeTypeEnum::TextType => TextareaType::class,
                default => TextType::class,
            };

            $builder->add(
                'attribute_' . $attribute->getId(),
                $type,
                [
                    'constraints' => [new NotBlank()],
                    'label' => $attribute->getName(),
                    'mapped' => false,
                    'required' => false,
                ]
            );
        }
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
