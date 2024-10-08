<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $markdown = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $html = null;

    /**
     * @var Collection<int, Assembly>
     */
    #[ORM\OneToMany(targetEntity: Assembly::class, mappedBy: 'category')]
    private Collection $assemblies;

    public function __construct()
    {
        $this->assemblies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getMarkdown(): ?string
    {
        return $this->markdown;
    }

    public function setMarkdown(?string $markdown): static
    {
        $this->markdown = $markdown;

        return $this;
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): static
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @return Collection<int, Assembly>
     */
    public function getAssemblies(): Collection
    {
        return $this->assemblies;
    }

    public function addAssembly(Assembly $assembly): static
    {
        if (!$this->assemblies->contains($assembly)) {
            $this->assemblies->add($assembly);
            $assembly->setCategory($this);
        }

        return $this;
    }

    public function removeAssembly(Assembly $assembly): static
    {
        if ($this->assemblies->removeElement($assembly)) {
            // set the owning side to null (unless already changed)
            if ($assembly->getCategory() === $this) {
                $assembly->setCategory(null);
            }
        }

        return $this;
    }
}
