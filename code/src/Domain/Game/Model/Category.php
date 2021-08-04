<?php

declare(strict_types=1);

namespace App\Domain\Game\Model;

use App\Domain\Game\DTO\ConfiguratorDTO;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Infrastructure\Game\Repository\DoctrineCategoryRepository")
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="CategoryId")
     */
    private CategoryId $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="json", options={"jsonb": true})
     */
    private array $configurator;

    /**
     * Example: ["EUR" => "EUR", "USD" => "USD"].
     *
     * @ORM\Column(type="json", options={"jsonb": true})
     */
    private array $currencies = [];

    /**
     * @ORM\Column(type="datetime_immutable", name="created_at")
     *
     * @var \DateTimeImmutable
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", name="updated_at")
     *
     * @var \DateTimeImmutable
     */
    protected $updatedAt;

    public function __construct(CategoryId $id, string $name, ConfiguratorDTO $configurator)
    {
        $this->id = $id;
        $this->name = $name;
        $this->configurator = $configurator->toArray();
    }

    /** @ORM\PrePersist */
    public function onPrePersist(): void
    {
        $now = new \DateTimeImmutable();

        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /** @ORM\PreUpdate */
    public function onPreUpdate(): void
    {
        $now = new \DateTimeImmutable();

        $this->updatedAt = $now;
    }

    public function getId(): CategoryId
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConfigurator(): ConfiguratorDTO
    {
        return new ConfiguratorDTO($this->configurator);
    }

    public function setConfigurator(ConfiguratorDTO $configuratorDTO): self
    {
        $this->configurator = $configuratorDTO->toArray();

        return $this;
    }

    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    public function setCurrencies(array $currencies): self
    {
        $this->currencies = $currencies;

        return $this;
    }
}
