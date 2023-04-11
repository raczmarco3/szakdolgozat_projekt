<?php

namespace App\Dto\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

class RateRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Range(min: 1)]
    private int $product_id;
    #[Assert\NotBlank]
    #[Assert\Range(min: 1)]
    #[Assert\Range(max: 5)]
    private int $rating;

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

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }
}