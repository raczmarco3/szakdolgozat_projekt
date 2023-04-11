<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\MethodRequestDto;
use App\Dto\ResponseDto\MethodResponseDto;
use App\Entity\Method;
use App\Entity\User;
use App\Repository\MethodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class MethodService
{
    public function addNewMethod(MethodRepository $methodRepository, MethodRequestDto $methodRequestDto, User $user): JsonResponse
    {
        $method = $methodRepository->findOneBy(["name" => $methodRequestDto->getName()]);

        if($method) {
            return new JsonResponse(["msg" => "This payment method already exists!"], 403);
        }

        $method = new Method();
        $method->setName($methodRequestDto->getName());
        $method->setUser($user);

        $methodRepository->save($method, true);
        return new JsonResponse(["msg" => "Payment method created!"], 201);
    }

    public function getMethods(SerializerInterface $serializer, MethodRepository $methodRepository): JsonResponse
    {
        $methods = $methodRepository->findAll();
        if(empty($methods)) {
            return new JsonResponse(["msg" => "There are no payment methods yet!"], 404);
        }

        $methodResponseDtoArray = [];

        foreach ($methods as $method)
        {
            $methodResponseDto = new MethodResponseDto();
            $methodResponseDto->setId($method->getId());
            $methodResponseDto->setName($method->getName());
            $methodResponseDto->setUserId($method->getUser()->getId());

            $methodResponseDtoArray[] = $methodResponseDto;
        }
        return JsonConverter::jsonResponseConverter($serializer, $methodResponseDtoArray);
    }

    public function deleteMethod(int $id, MethodRepository $methodRepository): JsonResponse
    {
        $method = $methodRepository->find($id);
        if(!$method) {
            return new JsonResponse(["meg" => "Payment method not found!"], 404);
        }

        $methodRepository->remove($method, true);
        return new JsonResponse(["msg" => "Payment method deleted!"], 200);
    }

    public function editMethod($id, MethodRepository $methodRepository, MethodRequestDto $methodRequestDto,
                               EntityManagerInterface $entityManager): JsonResponse
    {
        $method = $methodRepository->find($id);
        if(!$method) {
            return new JsonResponse(["meg" => "Payment method not found!"], 404);
        }

        if($method->getName() != $methodRequestDto->getName()
            && $methodRepository->findOneBy(["name" => $methodRequestDto->getName()])) {
            return new JsonResponse(["msg" => "This payment method already exists!"], 403);
        }

        $method->setName($methodRequestDto->getName());
        $entityManager->flush();

        return new JsonResponse(["msg" => "Payment method updated!"], 200);
    }

}