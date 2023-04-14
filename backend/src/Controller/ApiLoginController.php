<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login')]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(["msg" => "Missing credentials!"], 401);
        }

        return new JsonResponse([
            "username" => $user->getUsername(),
            "role" => $user->getRoles()[0],
        ], 200);
    }

    #[Route('/api/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    { }
}
