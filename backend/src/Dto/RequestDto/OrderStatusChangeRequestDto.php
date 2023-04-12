<?php

namespace App\Dto\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

class OrderStatusChangeRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    private int $order_id;
    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    private int $status_id;

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->order_id;
    }

    /**
     * @param int $order_id
     */
    public function setOrderId(int $order_id): void
    {
        $this->order_id = $order_id;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->status_id;
    }

    /**
     * @param int $status_id
     */
    public function setStatusId(int $status_id): void
    {
        $this->status_id = $status_id;
    }
}