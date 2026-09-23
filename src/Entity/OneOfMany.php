<?php

namespace App\Entity;

use App\Repository\OneOfManyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: OneOfManyRepository::class)]
class OneOfMany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Option value cannot be empty')]
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'oneOfManies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AttributeCv $attribute = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }
      public function getAttribute(): ?AttributeCv
    {
        return $this->attribute;
    }

    public function setAttribute(?AttributeCv $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

}
