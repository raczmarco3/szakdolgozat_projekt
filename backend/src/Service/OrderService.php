<?php

namespace App\Service;

use App\Dto\RequestDto\CartRequestDto;
use App\Dto\RequestDto\OrderRequestDto;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\MethodRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\StatusRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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

}