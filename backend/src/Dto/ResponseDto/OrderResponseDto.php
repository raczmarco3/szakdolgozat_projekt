<?php

namespace App\Dto\ResponseDto;

class OrderResponseDto
{
    private int $id;
    private int $user_id;
    private array $productsResponseDtoArray;
    private string $address;
    private ?\DateTimeImmutable $createdAt;
    private String $statusName;
    private String $methodName;

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
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return array
     */
    public function getProductsResponseDtoArray(): array
    {
        return $this->productsResponseDtoArray;
    }

    /**
     * @param array $productsResponseDtoArray
     */
    public function setProductsResponseDtoArray(array $productsResponseDtoArray): void
    {
        $this->productsResponseDtoArray = $productsResponseDtoArray;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return String
     */
    public function getStatusName(): string
    {
        return $this->statusName;
    }

    /**
     * @param String $statusName
     */
    public function setStatusName(string $statusName): void
    {
        $this->statusName = $statusName;
    }

    /**
     * @return String
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @param String $methodName
     */
    public function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }
}