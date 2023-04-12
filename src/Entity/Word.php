<?php

namespace App\Entity;

use App\Repository\WordRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ReflectionClass;

#[ORM\Entity(repositoryClass: WordRepository::class)]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $popularity_score = null;

    #[ORM\Column(length: 255)]
    private ?string $source = null;

    #[ORM\Column(nullable: true)]
    private ?float $positive_count = null;

    #[ORM\Column(nullable: true)]
    private ?float $negative_count = null;

    #[ORM\Column(nullable: true)]
    private ?float $total_count = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPopularityScore(): ?float
    {
        return $this->popularity_score;
    }

    public function setPopularityScore(?float $popularity_score): self
    {
        $this->popularity_score = $popularity_score;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPositiveCount(): ?float
    {
        return $this->positive_count;
    }

    /**
     * @param float|null $positive_count
     */
    public function setPositiveCount(?float $positive_count): void
    {
        $this->positive_count = $positive_count;
    }

    /**
     * @return float|null
     */
    public function getNegativeCount(): ?float
    {
        return $this->negative_count;
    }

    /**
     * @param float|null $negative_count
     */
    public function setNegativeCount(?float $negative_count): void
    {
        $this->negative_count = $negative_count;
    }

    /**
     * @return float|null
     */
    public function getTotalCount(): ?float
    {
        return $this->total_count;
    }

    /**
     * @param float|null $total_count
     */
    public function setTotalCount(?float $total_count): void
    {
        $this->total_count = $total_count;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeInterface $created_at
     */
    public function setCreatedAt(DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getClassName(): string
    {
        return strtolower((new ReflectionClass($this))->getShortName());
    }
}
