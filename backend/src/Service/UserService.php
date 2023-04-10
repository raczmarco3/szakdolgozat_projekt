<?php

namespace App\Service;

use App\Dto\RequestDto\UserRequestDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function register(UserRepository $userRepository, UserRequestDto $userRequestDto, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $userRepository->findOneBy(["username" => $userRequestDto->getUsername()]);

        if($user) {
            return new JsonResponse(["msg" => "This user already exists!"], 403);
        }

        $user = new User();
        $user->setUsername($userRequestDto->getUsername());

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $userRequestDto->getPassword()
        );
        $user->setPassword($hashedPassword);

        $userRepository->save($user, true);
        return new JsonResponse(["msg" => "Registration successful!"], 201);
    }

}