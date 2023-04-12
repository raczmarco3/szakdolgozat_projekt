<?php

namespace App\Controller;

use App\Converter\ValidationErrorJsonConverter;
use App\Dto\RequestDto\OrderRequestDto;
use App\Dto\RequestDto\OrderStatusChangeRequestDto;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ImageRepository;
use App\Repository\MethodRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\StatusRepository;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/add", methods={"POST"})
     */
    public function orderProducts(OrderRepository $orderRepository, EntityManagerInterface $entityManager,
                                  CartRepository $cartRepository, #[CurrentUser] ?User $user,
                                  ProductRepository $productRepository, MethodRepository $methodRepository,
                                  StatusRepository $statusRepository, OrderService $orderService,
                                  SerializerInterface $serializer, ValidatorInterface $validator, Request $request): JsonResponse
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

        $orderRequestDto = $serializer->deserialize($request->getContent(), OrderRequestDto::class, 'json');

        $errors = $validator->validate($orderRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $orderService->orderProducts($orderRepository, $orderRequestDto, $entityManager, $cartRepository, $user,
            $productRepository, $methodRepository, $statusRepository);
    }

    /**
     * @Route("/get", methods={"GET"})
     */
    public function getOrders(OrderRepository $orderRepository, ProductRepository $productRepository,
                              SerializerInterface $serializer, ImageRepository $imageRepository,
                              OrderService $orderService): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        return $orderService->getOrders($orderRepository, $productRepository, $serializer, $imageRepository, null);
    }

    /**
     * @Route("/get/own", methods={"GET"})
     */
    public function getOwnOrders(OrderRepository $orderRepository, ProductRepository $productRepository,
                              SerializerInterface $serializer, ImageRepository $imageRepository,
                              OrderService $orderService, #[CurrentUser] ?User $user): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_USER');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        return $orderService->getOrders($orderRepository, $productRepository, $serializer, $imageRepository, $user);
    }

    /**
     * @Route("/status", methods={"PUT"})
     */
    public function changeOrderStatus(StatusRepository $statusRepository, OrderRepository $orderRepository,
                                      #[CurrentUser] ?User $user, EntityManagerInterface $entityManager,
                                      OrderService $orderService, SerializerInterface $serializer,
                                      ValidatorInterface $validator, Request $request): JsonResponse
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

        $orderStatusChangeRequestDto = $serializer->deserialize($request->getContent(), OrderStatusChangeRequestDto::class, 'json');

        $errors = $validator->validate($orderStatusChangeRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $orderService->changeOrderStatus($statusRepository, $orderRepository, $user, $orderStatusChangeRequestDto, $entityManager);
    }


}