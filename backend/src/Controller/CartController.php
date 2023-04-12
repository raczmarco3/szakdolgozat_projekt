<?php

namespace App\Controller;

use App\Converter\ValidationErrorJsonConverter;
use App\Dto\RequestDto\CartRequestDto;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/cart")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/add", methods={"POST"})
     */
    public function addToCart(CartRepository $cartRepository, ProductRepository $productRepository,
                              EntityManagerInterface $entityManager, #[CurrentUser] ?User $user, SerializerInterface $serializer,
                              Request $request, CartService $cartService, ValidatorInterface $validator): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_USER');
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

        $cartRequestDto = $serializer->deserialize($request->getContent(), CartRequestDto::class, 'json');

        $errors = $validator->validate($cartRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $cartService->addToCart($cartRepository, $cartRequestDto, $productRepository, $entityManager, $user);
    }

    /**
     * @Route("/get", methods={"GET"})
     */
    public function getCart(CartRepository $cartRepository, SerializerInterface $serializer, ProductRepository $productRepository,
                            #[CurrentUser] ?User $user, CartService $cartService, ImageRepository $imageRepository,
                            EntityManagerInterface $entityManager): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_USER');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        return $cartService->getCart($cartRepository, $user, $serializer, $productRepository, $imageRepository, $entityManager);
    }

    /**
     * @Route("/remove", methods={"POST"})
     */
    public function removeFromCart(CartRepository $cartRepository, EntityManagerInterface $entityManager,
                                   #[CurrentUser] ?User $user, CartService $cartService, Request $request,
                                   ValidatorInterface $validator, SerializerInterface $serializer): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_USER');
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

        $cartRequestDto = $serializer->deserialize($request->getContent(), CartRequestDto::class, 'json');

        $errors = $validator->validate($cartRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $cartService->removeFromCart($cartRepository, $cartRequestDto, $entityManager, $user);
    }

}