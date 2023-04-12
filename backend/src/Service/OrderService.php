<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\OrderRequestDto;
use App\Dto\ResponseDto\OrderResponseDto;
use App\Dto\ResponseDto\ProductResponseDto;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ImageRepository;
use App\Repository\MethodRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\StatusRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class OrderService
{
    public function orderProducts(OrderRepository $orderRepository, OrderRequestDto $orderRequestDto, EntityManagerInterface $entityManager,
                                  CartRepository $cartRepository, User $user, ProductRepository $productRepository,
                                  MethodRepository $methodRepository, StatusRepository $statusRepository): JsonResponse
    {
        $cart = $cartRepository->findOneBy(["user" => $user]);
        $diff = array_diff($cart->getProducts(), $orderRequestDto->getProducts());

        if(count($diff) > 0) {
            return new JsonResponse(["msg" => "The content of the 2 productsArray is different!"], 403);
        }
        $productsArray = $orderRequestDto->getProducts();
        $orderedProducts = [];

        foreach($productsArray as $product_id)
        {
            $product = $productRepository->find($product_id);
            if($product && $product->getDeleted() == 0) {
                $orderedProducts[] = $product_id;
            }
        }

        $method = $methodRepository->find($orderRequestDto->getMethodId());
        if(!$method) {
            return new JsonResponse(["Payment method not found!"], 404);
        }

        $status = $statusRepository->findOneBy(["name" => "Megrendelés feladva"]);

        if(!$status) {
            $status = "Status error.";
        }

        $order = new Order();
        $order->setProducts($orderedProducts);
        $order->setUser($user);
        $order->setCreatedAt(new DateTimeImmutable("now"));
        $order->setAddress($orderRequestDto->getAddress());
        $order->setMethod($method);
        $order->setStatus($status);

        $orderRepository->save($order, true);

        //remove products from cart after order
        $cart->setProducts([]);
        $entityManager->flush();

        return new JsonResponse(["msg" => "Order successful!"], 200);
    }

    public function getOrders(OrderRepository $orderRepository, ProductRepository $productRepository,
                              SerializerInterface $serializer, ImageRepository $imageRepository, ?User $user): JsonResponse
    {
        if(is_null($user)) {
            $orders = $orderRepository->findAll();
            if(empty($orders)) {
                return new JsonResponse(["msg" => "There are no orders yet!"], 404);
            }
        } else {
            $orders = $orderRepository->findBy(["user" => $user]);
            if(empty($orders)) {
                return new JsonResponse(["msg" => "You haven't had any orders yet!"], 404);
            }
        }

        $orderResponseDtoArray = [];

        foreach ($orders as $order)
        {
            $productsResponseDtoArray = [];
            $products = $order->getProducts();

            foreach ($products as $product_id)
            {
                $product = $productRepository->find($product_id);
                if($product) {
                    $productResponseDto = new ProductResponseDto();
                    $productResponseDto->setId($product->getId());
                    $productResponseDto->setPrice($product->getPrice());
                    $productResponseDto->setName($product->getName());
                    $productResponseDto->setCategory($product->getCategory()->getName());
                    $productResponseDto->setUpdatedAt($product->getUpdatedAt());
                    $productResponseDto->setCreatedAt($product->getCreatedAt());

                    $imageData = $imageRepository->findOneBy(["product" => $product]);
                    $productResponseDto->setImageData($imageData->getImage());

                    $productsResponseDtoArray[] = $productResponseDto;
                }
            }

            $orderResponseDto = new OrderResponseDto();
            $orderResponseDto->setProductsResponseDtoArray($productsResponseDtoArray);
            $orderResponseDto->setUserId($order->getUser()->getId());
            $orderResponseDto->setId($order->getId());
            $orderResponseDto->setAddress($order->getAddress());
            $orderResponseDto->setMethodName($order->getMethod()->getName());
            $orderResponseDto->setStatusName($order->getStatus()->getName());
            $orderResponseDto->setCreatedAt($order->getCreatedAt());

            $orderResponseDtoArray[] = $orderResponseDto;
        }

        return JsonConverter::jsonResponseConverter($serializer, $orderResponseDtoArray);
    }

}