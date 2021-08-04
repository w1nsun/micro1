<?php

declare(strict_types=1);

namespace App\Application\Game\Command;

use App\Application\Game\Validator\ConfiguratorPropertyType as AssertConfiguratorPropertyType;
use App\Application\Game\Validator\CategoryExists as AssertCategoryExists;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrUpdateCategoryCommand
{
    /**
     * @Assert\Sequentially({
     *     @Assert\NotBlank(groups={"create", "update"}),
     *     @Assert\Type(type="int", groups={"create"}),
     *     @Assert\Positive(groups={"create"}),
     *     @AssertCategoryExists(groups={"create"})
     * }, groups={"create", "update"})
     */
    private $categoryId;

    /**
     * @Assert\NotBlank(groups={"create", "update"})
     * @Assert\Type(type="string", groups={"create", "update"})
     * @Assert\Length(min=2, max=255, groups={"create", "update"})
     */
    private $name;

    /**
     * @Assert\Sequentially({
     *     @Assert\Type(type="array", groups={"create", "update"}),
     *     @AssertConfiguratorPropertyType(groups={"create", "update"})
     * }, groups={"create", "update"})
     */
    private $configurator;

    /**
     * @Assert\NotBlank(groups={"create", "update"})
     * @Assert\Type(type="array", groups={"create", "update"})
     * @Assert\All({
     *     @Assert\NotBlank(groups={"create", "update"}),
     *     @Assert\Currency(groups={"create", "update"})
     * }, groups={"create", "update"})
     */
    private $currencies;

    public function __construct(
        $categoryId,
        $name = null,
        $configurator = null,
        $currencies = null
    ) {
        $this->categoryId = $categoryId;
        $this->name = $name;
        $this->configurator = $configurator;
        $this->currencies = $currencies;
    }

    public function getCategoryId(): int
    {
        return (int) $this->categoryId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConfigurator(): array
    {
        return $this->configurator ?: [];
    }

    public function getCurrencies(): array
    {
        if (!$this->currencies) {
            return [];
        }

        array_walk($this->currencies, static function (&$currency): void {
            $currency = \is_string($currency) ? strtoupper($currency) : $currency;
        });
        $currencies = array_values($this->currencies);
        $this->currencies = array_combine($currencies, $currencies);

        return $this->currencies;
    }
}
