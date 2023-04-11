<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\ProductRequestDto;
use App\Dto\ResponseDto\CategoryResponseDto;
use App\Dto\ResponseDto\ProductResponseDto;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ProductService
{
    public function addNewProduct(ProductRepository $productRepository, CategoryRepository $categoryRepository,
                                  ProductRequestDto $productRequestDto, User $user): JsonResponse
    {
        $product = $productRepository->findOneBy(["name" => $productRequestDto->getName()]);

        if($product) {
            return new JsonResponse(["msg" => "This product already exists!"], 403);
        }

        $product = new Product();
        $product = $this->setProduct($product, $productRequestDto, $categoryRepository);

        $category = $categoryRepository->find($productRequestDto->getCategoryId());
        if(!$category) {
            return new JsonResponse(["msg" => "Category not found!"], 404);
        }
        $product->setCategory($category);
        $product->setUser($user);

        $productRepository->save($product, true);
        return new JsonResponse(["msg" => "Product created!"], 201);
    }

    public function getProducts(SerializerInterface $serializer, ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();

        if(empty($products)) {
            return new JsonResponse(["msg" => "There are no products yet!"], 404);
        }

        $productResponseDtoArray = [];

        foreach($products as $product)
        {
            if($product->getDeleted() == 0) {
                $productResponseDto = new ProductResponseDto();
                $productResponseDto->setId($product->getId());
                $productResponseDto->setName($product->getName());
                $productResponseDto->setPrice($product->getPrice());
                $productResponseDto->setCategory($product->getCategory()->getName());
                $productResponseDto->setCreatedAt($product->getCreatedAt());
                $productResponseDto->setUpdatedAt($product->getUpdatedAt());

                $productResponseDtoArray[] = $productResponseDto;
            }
        }
        return JsonConverter::jsonResponseConverter($serializer, $productResponseDtoArray);
    }

    public function deleteProduct($id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if(!$product) {
            return new JsonResponse(["msg" => "Product not found!"], 404);
        }

        $productRepository->remove($product, true);
        return new JsonResponse(["msg" => "Product deleted!"], 200);
    }

    public function editProduct($id, ProductRepository $productRepository, CategoryRepository $categoryRepository,
                                ProductRequestDto $productRequestDto, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if(!$product) {
            return new JsonResponse(["msg" => "Product not found!"], 404);
        }

        if($product->getName() != $productRequestDto->getName() && $productRepository->findOneBy(["name" => $productRequestDto->getName()])) {
            return new JsonResponse(["msg" => "This product name already exists!"], 403);
        }

        $product = $this->setProduct($product, $productRequestDto);
        $category = $categoryRepository->find($productRequestDto->getCategoryId());
        if(!$category) {
            return new JsonResponse(["msg" => "Category not found!"], 404);
        }

        $product->setCategory($category);
        $entityManager->flush();

        return new JsonResponse(["msg" => "Product updated!"], 200);
    }

    private function setProduct(Product $product, ProductRequestDto $productRequestDto): Product
    {
        $product->setName($productRequestDto->getName());
        $product->setPrice($productRequestDto->getPrice());
        $product->setDeleted(0);

        $date = new DateTimeImmutable("now");
        $product->setUpdatedAt($date);

        return $product;
    }
}