<?php

namespace App\Service;

use App\Dto\RequestDto\UserRequestDto;
use App\Entity\Cart;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function register(UserRepository $userRepository, UserRequestDto $userRequestDto,
                             UserPasswordHasherInterface $passwordHasher, CartRepository $cartRepository): JsonResponse
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

        $createdUser = $userRepository->findOneBy(["username" => $user->getUsername()]);

        $cart = new Cart();
        $cart->setUser($createdUser);

        $cartRepository->save($cart, true);
        return new JsonResponse(["msg" => "Registration successful!"], 201);
    }

}