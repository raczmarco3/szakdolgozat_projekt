<?php

namespace App\Controller;

use App\Dto\RequestDto\MethodRequestDto;
use App\Entity\User;
use App\Repository\MethodRepository;
use App\Service\MethodService;
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

}