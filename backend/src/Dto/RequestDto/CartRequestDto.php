<?php

namespace App\Dto\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

class CartRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Range(min: 1)]
    private int $product_id;

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->product_id;
    }

    /**
     * @param int $product_id
     */
    public function setProductId(int $product_id): void
    {
        $this->product_id = $product_id;
    }
}