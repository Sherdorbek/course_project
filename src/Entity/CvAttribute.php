<?php

namespace App\Entity;

use App\Repository\CvAttributeRepository;
use BcMath\Number;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CvAttributeRepository::class)]
class CvAttribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AttributeCv $attribute = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $valString = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $valText = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $valDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $valDatePeriod = null;

    #[ORM\Column(nullable: true)]
    private ?bool $valBool = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $valDropdown = null;

    #[ORM\Column(length: 510, nullable: true)]
    private ?string $valImage = null;

    #[ORM\Column(type: Types::DECIMAL,precision: 10, scale: 2, nullable: true)]
    private ?string $valNumber = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValString(): ?string
    {
        return $this->valString;
    }

    public function setValString(?string $valString): static
    {
        $this->valString = $valString;

        return $this;
    }

    public function getValText(): ?string
    {
        return $this->valText;
    }

    public function setValText(?string $valText): static
    {
        $this->valText = $valText;

        return $this;
    }

    public function getValDate(): ?\DateTimeImmutable
    {
        return $this->valDate;
    }

    public function setValDate(?\DateTimeImmutable $valDate): static
    {
        $this->valDate = $valDate;

        return $this;
    }

    public function getValDatePeriod(): ?\DateTimeImmutable
    {
        return $this->valDatePeriod;
    }

    public function setValDatePeriod(?\DateTimeImmutable $valDatePeriod): static
    {
        $this->valDatePeriod = $valDatePeriod;

        return $this;
    }

    public function isValBool(): ?bool
    {
        return $this->valBool;
    }

    public function setValBool(?bool $valBool): static
    {
        $this->valBool = $valBool;

        return $this;
    }

    public function getValDropdown(): ?string
    {
        return $this->valDropdown;
    }

    public function setValDropdown(string $valDropdown): static
    {
        $this->valDropdown = $valDropdown;

        return $this;
    }

    public function getValImage(): ?string
    {
        return $this->valImage;
    }

    public function setValImage(?string $valImage): static
    {
        $this->valImage = $valImage;

        return $this;
    }

    public function getValNumber(): ?string
    {
        return $this->valNumber;
    }

    public function setValNumber(?string $valNumber): static
    {
        $this->valNumber = $valNumber;

        return $this;
    }
}
