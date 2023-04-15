<?php

namespace App\Controller;

use App\Converter\ValidationErrorJsonConverter;
use App\Dto\RequestDto\StatusRequestDto;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\StatusRepository;
use App\Service\StatusService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/status")
 */
class StatusController extends AbstractController
{
    /**
     * @Route("/add", methods={"POST"})
     */
    public function addStatus(StatusRepository $statusRepository, #[CurrentUser] ?User $user, StatusService $statusService,
                              SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        $acceptableContentTypes = $request->getAcceptableContentTypes();

        if(empty($request->getContent())) {
            return new JsonResponse(["msg" => "HTTP body is empty"], 400);
        } else if(count($acceptableContentTypes)>1 || $acceptableContentTypes[0] != "application/json") {
            return new JsonResponse(["msg" => "application/json is the only acceptable content type!"], 406);
        } else if(json_decode($request->getContent()) === null) {
            return new JsonResponse(["msg" => "The content of the body should be json!"], 400);
        }

        $statusRequestDto = $serializer->deserialize($request->getContent(), StatusRequestDto::class, 'json');

        $errors = $validator->validate($statusRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $statusService->addStatus($statusRepository, $statusRequestDto, $user);
    }

    /**
     * @Route("/get", methods={"GET"})
     */
    public function getStatuses(SerializerInterface $serializer, StatusRepository $statusRepository,
                                StatusService $statusService): JsonResponse
    {
        return $statusService->getStatuses($serializer, $statusRepository);
    }

    /**
     * @Route("/delete/{id}", methods={"DELETE"})
     */
    public function deleteStatus($id, StatusRepository $statusRepository, StatusService $statusService, OrderRepository $orderRepository): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        if(!is_numeric($id)) {
            return new JsonResponse(["msg" => "id must be a number!"], 422);
        }

        return $statusService->deleteStatus($id, $statusRepository, $orderRepository);
    }

    /**
     * @Route("/edit/{id}", methods={"PUT"})
     */
    public function editStatus($id, StatusRepository $statusRepository, EntityManagerInterface $entityManager, Request $request,
                               StatusService $statusService, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        $acceptableContentTypes = $request->getAcceptableContentTypes();

        if(empty($request->getContent())) {
            return new JsonResponse(["msg" => "HTTP body is empty"], 400);
        } else if(count($acceptableContentTypes)>1 || $acceptableContentTypes[0] != "application/json") {
            return new JsonResponse(["msg" => "application/json is the only acceptable content type!"], 406);
        } else if(json_decode($request->getContent()) === null) {
            return new JsonResponse(["msg" => "The content of the body should be json!"], 400);
        }

        $data = json_decode($request->getContent(), true);
        if(!isset($data["id"]) || !is_numeric($data["id"]) || !is_numeric($id)) {
            return new JsonResponse(["msg" => "id must be a number!"], 422);
        } else if($data["id"] != $id) {
            return new JsonResponse(["msg" => "You don't have permission to edit this status!"], 403);
        }

        $statusRequestDto = $serializer->deserialize($request->getContent(), StatusRequestDto::class, 'json');

        $errors = $validator->validate($statusRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $statusService->editStatus($id, $statusRepository, $statusRequestDto, $entityManager);
    }

}