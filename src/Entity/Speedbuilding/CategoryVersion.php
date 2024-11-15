<?php

namespace App\Entity\Speedbuilding;

use App\Entity\User\User;
use App\Repository\Speedbuilding\CategoryVersionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryVersionRepository::class)]
#[ORM\Table(name: 'speedbuilding_category_version')]
class CategoryVersion
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'versions', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $markdown = null;

    #[ORM\Column]
    private ?int $version = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __clone()
    {
        $this->id = null;
        // force app logic to define status (and prevent db insert accidentally)
        $this->status = null;
        $this->version++;
        $this->createdAt = new \DateTimeImmutable();
        $this->author = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
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

    public function getMarkdown(): ?string
    {
        return $this->markdown;
    }

    public function setMarkdown(string $markdown): static
    {
        $this->markdown = $markdown;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isDraft(): bool
    {
        return ($this->status == self::STATUS_DRAFT);
    }

    public function isPublished(): bool
    {
        return ($this->status == self::STATUS_PUBLISHED);
    }

    /**
     * Check if name and markdown fields are identical to those of Category
     */
    public function isIdentical()
    {
        return (($this->name == $this->category->getName()) && ($this->markdown == $this->category->getMarkdown()));
    }
}
