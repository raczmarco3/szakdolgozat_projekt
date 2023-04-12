<?php

namespace App\Dto\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

class OrderRequestDto
{
    #[Assert\NotBlank]
    private array $products;
    #[Assert\NotBlank]
    private String $address;
    #[Assert\NotBlank]
    #[Assert\Range(min: 1)]
    private int $method_id;

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    /**
     * @return String
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param String $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return int
     */
    public function getMethodId(): int
    {
        return $this->method_id;
    }

    /**
     * @param int $method_id
     */
    public function setMethodId(int $method_id): void
    {
        $this->method_id = $method_id;
    }
}