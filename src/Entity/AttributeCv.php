<?php

namespace App\Entity;

use App\Enum\AttributeTypeEnum;
use App\Repository\AttributeCvRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity(repositoryClass: AttributeCvRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NAME', fields: ['name'])]
#[UniqueEntity(fields: ['name'], message: 'There is already an attribute with this name')]

class AttributeCv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AttributeCategory $category = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: AttributeTypeEnum::class)]
    private ?AttributeTypeEnum $type = null;

    /**
     * @var Collection<int, OneOfMany>
     */
    #[ORM\OneToMany(targetEntity: OneOfMany::class, mappedBy: 'attribute', orphanRemoval: true, cascade: ['persist'])]
    #[Assert\Valid]
    private Collection $oneOfManies;

    public function __construct()
    {
        $this->oneOfManies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?AttributeCategory
    {
        return $this->category;
    }

    public function setCategory(?AttributeCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?AttributeTypeEnum
    {
        return $this->type;
    }

    public function setType(AttributeTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, OneOfMany>
     */
    public function getOneOfManies(): Collection
    {
        return $this->oneOfManies;
    }

    public function addOneOfMany(OneOfMany $oneOfMany): static
    {
        if (!$this->oneOfManies->contains($oneOfMany)) {
            $this->oneOfManies->add($oneOfMany);
            $oneOfMany->setAttribute($this);
        }

        return $this;
    }

    public function removeOneOfMany(OneOfMany $oneOfMany): static
    {
        $this->oneOfManies->removeElement($oneOfMany);

        return $this;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->type === AttributeTypeEnum::OneOfMany && $this->oneOfManies->isEmpty()) {
            $context->buildViolation('You must provide at least one option.')
                ->atPath('oneOfManies')
                ->addViolation();
        }
    }

}
