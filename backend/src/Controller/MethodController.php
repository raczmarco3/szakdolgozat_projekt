<?php

namespace App\Controller;

use App\Dto\RequestDto\MethodRequestDto;
use App\Entity\User;
use App\Repository\MethodRepository;
use App\Service\MethodService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 * @Route("/api/method")
 */
class MethodController extends AbstractController
{
    /**
     * @Route("/add", methods={"POST"})
     */
    public function addNewMethod(MethodRepository $methodRepository, MethodService $methodService, #[CurrentUser] ?User $user,
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

        $methodRequestDto = $serializer->deserialize($request->getContent(), MethodRequestDto::class, 'json');

        return $methodService->addNewMethod($methodRepository, $methodRequestDto, $user);
    }

    /**
     * @Route("/all", methods={"GET"})
     */
    public function getMethods(SerializerInterface $serializer, MethodRepository $methodRepository,
                                  MethodService $methodService): JsonResponse
    {
        return $methodService->getMethods($serializer, $methodRepository);
    }

    /**
     * @Route("/delete/{id}", methods={"DELETE"})
     */
    public function deleteMethod($id, MethodRepository $methodRepository, MethodService $methodService): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        if(!is_numeric($id)) {
            return new JsonResponse(["msg" => "id must be a number!"], 422);
        }

        return $methodService->deleteMethod($id, $methodRepository);
    }

    /**
     * @Route("/edit/{id}", methods={"PUT"})
     */
    public function editMethod($id, MethodRepository $methodRepository, EntityManagerInterface $entityManager,
                               MethodService $methodService, SerializerInterface $serializer, Request $request): JsonResponse
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
            return new JsonResponse(["msg" => "You don't have permission to edit this payment method!"], 403);
        }

        $methodRequestDto = $serializer->deserialize($request->getContent(), MethodRequestDto::class, 'json');

        return $methodService->editMethod($id, $methodRepository, $methodRequestDto, $entityManager);
    }

}