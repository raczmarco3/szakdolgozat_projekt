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
        min: 1
    )]
    private mixed $price;
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 1
    )]
    private mixed $category_id;

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
     * @return mixed
     */
    public function getPrice(): mixed
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice(mixed $price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getCategoryId(): mixed
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     */
    public function setCategoryId(mixed $category_id): void
    {
        $this->category_id = $category_id;
    }
}