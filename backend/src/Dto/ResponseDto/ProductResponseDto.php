<?php

namespace App\Dto\ResponseDto;

class ProductResponseDto
{
    private int $id;
    private string $name;
    private int $price;
    private string $categoryName;
    private ?\DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;
    private $imageData;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

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
     * @return string
     */
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }

    /**
     * @param string $categoryName
     */
    public function setCategoryName(string $categoryName): void
    {
        $this->categoryName = $categoryName;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt->format("Y-m-d h:i:s");
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt->format("Y-m-d h:i:s");
    }

    /**
     * @param \DateTimeImmutable|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getImageData()
    {
        return $this->imageData;
    }

    public function setImageData($imageData)
    {
        $this->imageData = $imageData;
    }
}