<?php

namespace App\Controller;

use App\Dto\RequestDto\UserRequestDto;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", methods={"POST"})
     */
    public function register(Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $passwordHasher,
                             UserService $userService, UserRepository $userRepository, CartRepository $cartRepository): JsonResponse
    {
        $acceptableContentTypes = $request->getAcceptableContentTypes();

        if(empty($request->getContent())) {
            return new JsonResponse(["msg" => "HTTP body is empty"], 400);
        } else if(count($acceptableContentTypes)>1 || $acceptableContentTypes[0] != "application/json") {
            return new JsonResponse(["msg" => "application/json is the only acceptable content type!"], 406);
        } else if(json_decode($request->getContent()) === null) {
            return new JsonResponse(["msg" => "The content of the body should be json!"], 400);
        }

        $userRequestDto = $serializer->deserialize($request->getContent(), UserRequestDto::class, 'json');

        return $userService->register($userRepository, $userRequestDto, $passwordHasher, $cartRepository);
    }
}