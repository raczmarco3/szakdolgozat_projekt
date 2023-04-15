<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\StatusRequestDto;
use App\Dto\ResponseDto\StatusResponseDto;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class StatusService
{
    public function addStatus(StatusRepository $statusRepository, StatusRequestDto $statusRequestDto, User $user): JsonResponse
    {
        $status = $statusRepository->findOneBy(["name" => $statusRequestDto->getName()]);

        if($status) {
            return new JsonResponse(["msg" => "This status already exists!"], 403);
        }

        $status = new Status();
        $status->setName($statusRequestDto->getName());
        $status->setUser($user);

        $statusRepository->save($status, true);
        return new JsonResponse(["msg" => "Status created!"], 201);
    }

    public function getStatuses(SerializerInterface $serializer, StatusRepository $statusRepository): JsonResponse
    {
        $statuses = $statusRepository->findAll();
        if(empty($statuses)) {
            return new JsonResponse(["msg" => "There are no statuses yet!"], 404);
        }

        $statusResponseDtoArray = [];

        foreach ($statuses as $status)
        {
            $statusResponseDto = new StatusResponseDto();

            $statusResponseDto->setId($status->getId());
            $statusResponseDto->setUserId($status->getUser()->getId());
            $statusResponseDto->setName($status->getName());

            $statusResponseDtoArray[] = $statusResponseDto;
        }

        return JsonConverter::jsonResponseConverter($serializer, $statusResponseDtoArray);
    }

    public function deleteStatus(int $id, StatusRepository $statusRepository, OrderRepository $orderRepository): JsonResponse
    {
        $status = $statusRepository->find($id);
        if(!$status) {
            return new JsonResponse(["msg" => "Status not found!"], 404);
        }

        $order = $orderRepository->findOneBy(["status" => $status]);
        if($order) {
            return new JsonResponse(["msg" => "This status is still in use!"], 403);
        }

        $statusRepository->remove($status, true);
        return new JsonResponse(["msg" => "Status deleted!"], 200);
    }

    public function editStatus(int $id, StatusRepository $statusRepository, StatusRequestDto $statusRequestDto,
                               EntityManagerInterface $entityManager): JsonResponse
    {
        $status = $statusRepository->find($id);
        if(!$status) {
            return new JsonResponse(["msg" => "Status not found!"], 404);
        }

        if($status->getName() != $statusRequestDto->getName()
            && $statusRepository->findOneBy(["name" => $statusRequestDto->getName()])) {
            return new JsonResponse(["msg" => "This status already exists!"], 403);
        }

        $status->setName($statusRequestDto->getName());
        $entityManager->flush();

        return new JsonResponse(["msg" => "Status updated!"], 200);
    }

}