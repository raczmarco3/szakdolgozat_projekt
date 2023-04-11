<?php

namespace App\Service;

use App\Dto\RequestDto\MethodRequestDto;
use App\Entity\Method;
use App\Entity\User;
use App\Repository\MethodRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class MethodService
{
    public function addNewMethod(MethodRepository $methodRepository, MethodRequestDto $methodRequestDto, User $user): JsonResponse
    {
        $method = $methodRepository->findOneBy(["name" => $methodRequestDto->getName()]);

        if($method) {
            return new JsonResponse(["msg" => "This method already exists!"], 403);
        }

        $method = new Method();
        $method->setName($methodRequestDto->getName());
        $method->setUser($user);

        $methodRepository->save($method, true);

        return new JsonResponse(["msg" => "Method created!"], 201);
    }

}