<?php

namespace App\Controller\App\DTO;

class ProductsQueryDTO
{
    private const ORDER_BY_PROPERTIES = [
        'orderBy'   => null,
        'direction' => null,
    ];

    private const ASC  = 'ASC';
    private const DESC = 'DESC';

    /**
     * @var int|null
     */
     private ?int $id = null;

    /**
     * @var string|null
     */
     private ?string $name = null;

    /**
     * @var string|null
     */
     private ?string $category = null;

    /**
     * @var string|null
     */
     private ?string $description = null;

    /**
     * @var int|null
     */
     private ?int $fromWeight = null;

    /**
     * @var int|null
     */
     private ?int $toWeight = null;

    /**
     * @var string|null
     */
     private ?string $orderBy = null;

    /**
     * @var string|null
     */
     private ?string $direction = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|string|null $id
     *
     * @return $this
     */
    public function setId(int|string|null $id): self
    {
        if (is_string($id) && filter_var($id, FILTER_VALIDATE_INT) === false) {
            $id = null;
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     *
     * @return $this
     */
    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFromWeight(): ?int
    {
        return $this->fromWeight;
    }

    /**
     * @param int|string|null $fromWeight
     *
     * @return $this
     */
    public function setFromWeight(int|string|null $fromWeight): self
    {
        if (is_string($fromWeight) && filter_var($fromWeight, FILTER_VALIDATE_INT) === false) {
            $fromWeight = null;
        }

        $this->fromWeight = $fromWeight;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getToWeight(): ?int
    {
        return $this->toWeight;
    }

    /**
     * @param int|string|null $toWeight
     *
     * @return $this
     */
    public function setToWeight(int|string|null $toWeight): self
    {
        if (is_string($toWeight) && filter_var($toWeight, FILTER_VALIDATE_INT) === false) {
            $toWeight = null;
        }

        $this->toWeight = $toWeight;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * @param string|null $orderBy
     *
     * @return $this
     */
    public function setOrderBy(?string $orderBy): self
    {
        if ($orderBy === 'fake') {
            $orderBy = null;
        }

        $this->orderBy = $orderBy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @param string|null $direction
     *
     * @return $this
     */
    public function setDirection(?string $direction): self
    {
        if ($direction === 'fake') {
            $direction = null;
        }

        $this->direction = $direction;

        return $this;
    }

    /**
     * @return array
     */
    public function getFilterData(): array
    {
        $filterData = [];

        foreach ($this as $propertyName => $propertyValue) {
            if (isset(self::ORDER_BY_PROPERTIES[$propertyName]) || is_null($propertyValue)) {
                continue;
            }

            $filterData[$propertyName] = $propertyValue;
        }

        return $filterData;
    }

    /**
     * @return array
     */
    public function getOrderByData(): array
    {
        $orderBy   = $this->orderBy;
        $direction = $this->direction;

        if (is_null($orderBy)) {
            return [];
        }

        if (is_null($direction)) {
            $direction = 'DESC';
        }

        $direction = match ($direction) {
            'ASC'   => 'ASC',
            'DESC'  => 'DESC',
            default => false
        };

        if ($direction === false) {
            return [];
        }

        $orderByData = [];

        foreach ($this as $propertyName => $propertyValue) {
            if ($this->orderBy !== $propertyName || isset(self::ORDER_BY_PROPERTIES[$propertyName])) {
                continue;
            }

            $orderByData['orderBy'] = $propertyName;
            break;
        }

        $orderByData['direction'] = $direction;

        return $orderByData;
    }
}