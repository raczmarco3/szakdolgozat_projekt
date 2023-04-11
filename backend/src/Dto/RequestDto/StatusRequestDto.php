<?php

namespace App\Dto\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

class StatusRequestDto
{
    #[Assert\NotBlank]
    private string $name;

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
}