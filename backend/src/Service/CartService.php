<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\CartRequestDto;
use App\Dto\ResponseDto\CartResposneDto;
use App\Dto\ResponseDto\ProductResponseDto;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CartService
{
    public function addToCart(CartRepository $cartRepository, CartRequestDto $cartRequestDto, ProductRepository $productRepository,
                              EntityManagerInterface $entityManager, User $user): JsonResponse
    {
        $product = $productRepository->find($cartRequestDto->getProductId());

        if(!$product || $product->getDeleted() == 1) {
            return new JsonResponse(["msg" => "Product not found!"], 404);
        }

        $cart = $cartRepository->findOneBy(["user" => $user]);
        $productArray = $cart->getProducts();

        $productArray[] = $product->getId();
        $cart->setProducts($productArray);

        $entityManager->flush();
        return new JsonResponse(["msg" => "Product added to cart!"], 200);
    }

    public function getCart(CartRepository $cartRepository, User $user, SerializerInterface $serializer,
                            ProductRepository $productRepository, ImageRepository $imageRepository,
                            EntityManagerInterface $entityManager): JsonResponse
    {
        $cart = $cartRepository->findOneBy(["user" => $user]);
        $productsArray = $cart->getProducts();
        $productResponseDtoArray = [];
        $availableProducts = [];

        foreach ($productsArray as $product_id)
        {
            $product = $productRepository->find($product_id);
            if($product && $product->getDeleted() == 0) {
                $productResponseDto = new ProductResponseDto();
                $productResponseDto->setId($product->getId());
                $productResponseDto->setName($product->getName());
                $productResponseDto->setPrice($product->getPrice());
                $productResponseDto->setCategoryName($product->getCategory()->getName());
                $productResponseDto->setUpdatedAt($product->getUpdatedAt());
                $productResponseDto->setCreatedAt($product->getCreatedAt());

                $image = $imageRepository->findOneBy(["product" => $product]);
                if($image) {
                    $productResponseDto->setImageData($image->getImage());
                }

                $productResponseDtoArray[] = $productResponseDto;
                $availableProducts[] = $product_id;
            }
        }

        //if there are "deleted" products they can't be in the cart
        if(count($availableProducts) != count($productsArray)) {
            $cart->setProducts($availableProducts);
            $entityManager->flush();
        }

        $cartResponseDto = new CartResposneDto();
        $cartResponseDto->setUserId($user->getId());
        $cartResponseDto->setId($cart->getId());
        $cartResponseDto->setProducts($productResponseDtoArray);

        return JsonConverter::jsonResponseConverter($serializer, $cartResponseDto);
    }

    public function removeFromCart(CartRepository $cartRepository, CartRequestDto $cartRequestDto,
                                   EntityManagerInterface $entityManager, User $user): JsonResponse
    {
        $cart = $cartRepository->findOneBy(["user" => $user]);
        $productArray = $cart->getProducts();

        $key = array_search($cartRequestDto->getProductId(), $productArray);
        //$productArray = array_slice($productArray, $key, count($productArray)-1);
        if($key >= 0 && $key !== false) {
            unset($productArray[$key]);
            $productArray = array_values($productArray);
        }

        $cart->setProducts($productArray);
        $entityManager->flush();

        return new JsonResponse(["msg" => "Product removed from cart!"], 200);
    }

    public function getProductsNumberInCart(CartRepository $cartRepository, User $user): JsonResponse
    {
        $cart = $cartRepository->findOneBy(["user" => $user]);
        $products = $cart->getProducts();

        return new JsonResponse(["msg" => count($products)]);
    }
}