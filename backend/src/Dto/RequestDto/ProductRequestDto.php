<?php

namespace App\Dto\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

class ProductRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    #[Assert\Length(max: 255)]
    private string $name;
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1,
    )]
    private int $price;
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1,
    )]
    private int $category_id;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     */
    public function setCategoryId(int $category_id): void
    {
        $this->category_id = $category_id;
    }
}