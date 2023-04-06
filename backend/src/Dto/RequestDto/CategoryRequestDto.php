<?php

namespace App\Dto\RequestDto;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryRequestDto
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 1)]
    #[Assert\Length(max: 255)]
    private mixed $name;

    /**
     * @return mixed
     */
    public function getName(): mixed
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName(mixed $name): void
    {
        $this->name = $name;
    }

}