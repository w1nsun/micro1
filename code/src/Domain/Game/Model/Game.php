<?php

declare(strict_types=1);

namespace App\Domain\Game\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Infrastructure\Game\Repository\DoctrineGameRepository")
 * @ORM\Table(name="games", indexes={
 *     @ORM\Index(name="category_idx", columns={"category_id"})
 * })
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="GameId")
     */
    private GameId $id;

    /**
     * @ORM\Column(type="CategoryId", name="category_id")
     */
    private CategoryId $categoryId;

    /**
     * @ORM\Embedded(class="App\Domain\Game\Model\Platform", columnPrefix="desktop_")
     */
    private $desktop;

    /**
     * @ORM\Embedded(class="App\Domain\Game\Model\Platform", columnPrefix="mobile_")
     */
    private $mobile;

    /**
     * @ORM\Embedded(class="App\Domain\Game\Model\Platform", columnPrefix="android_")
     */
    private $android;

    /**
     * @ORM\Embedded(class="App\Domain\Game\Model\Platform", columnPrefix="ios_")
     */
    private $ios;

    /**
     * @ORM\Column(type="json", nullable=true, options={"jsonb": true})
     */
    private ?array $extraData = null;

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

    public function __construct(GameId $id, CategoryId $categoryId)
    {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->desktop = new Platform();
        $this->mobile = new Platform();
        $this->android = new Platform();
        $this->ios = new Platform();
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

    public function getId(): GameId
    {
        return $this->id;
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function setCategoryId(CategoryId $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getDesktop(): Platform
    {
        return $this->desktop;
    }

    public function setDesktop(Platform $desktop): self
    {
        $this->desktop = $desktop;

        return $this;
    }

    public function getMobile(): Platform
    {
        return $this->mobile;
    }

    public function setMobile(Platform $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getAndroid(): Platform
    {
        return $this->android;
    }

    public function setAndroid(Platform $android): self
    {
        $this->android = $android;

        return $this;
    }

    public function getIOS(): Platform
    {
        return $this->ios;
    }

    public function setIOS(Platform $ios): self
    {
        $this->ios = $ios;

        return $this;
    }

    public function getExtraData(): ?array
    {
        return $this->extraData;
    }

    public function setExtraData(?array $extraData): self
    {
        $this->extraData = $extraData;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'game_id' => $this->getId()->value(),
                'category_id' => $this->getCategoryId()->value(),
                'platform' => [
                    'desktop' => $this->getDesktop()->toArray(),
                    'mobile' => $this->getMobile()->toArray(),
                    'ios' => $this->getIOS()->toArray(),
                    'android' => $this->getAndroid()->toArray(),
                ],
            ],
            $this->getExtraData() ?: []
        );
    }
}
