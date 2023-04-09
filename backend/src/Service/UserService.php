<?php

namespace App\Service;

use App\Dto\RequestDto\UserRequestDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserService
{
    public function register(UserRepository $userRepository, UserRequestDto $userRequestDto): JsonResponse
    {
        $user = $userRepository->findOneBy(["username" => $userRequestDto->getUsername()]);

        if($user) {
            return new JsonResponse(["msg" => "This user already exists!"], 403);
        }

        $user = new User();
        $user->setUsername($userRequestDto->getUsername());
        $user->setPassword($userRequestDto->getPassword());

        $userRepository->save($user, true);
        return new JsonResponse(["msg" => "Registration successful!"], 201);
    }

}