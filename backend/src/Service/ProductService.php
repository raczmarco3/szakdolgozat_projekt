<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\ProductRequestDto;
use App\Dto\ResponseDto\ProductMainPageResponseDto;
use App\Dto\ResponseDto\ProductResponseDto;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Repository\RateRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class ProductService
{
    public function addNewProduct(ProductRepository $productRepository, CategoryRepository $categoryRepository,
                                  ProductRequestDto $productRequestDto, User $user, String $imgData,
                                  ImageRepository $imageRepository): JsonResponse
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

        if(!empty($imgData)) {
            $image = new Image();
            $image->setImage($imgData);
            $image->setProduct($product);
            $image->setUser($user);

            $imageRepository->save($image, true);
        }
        return new JsonResponse(["msg" => "Product created!"], 201);
    }

    public function getProducts(SerializerInterface $serializer, ProductRepository $productRepository,
                                ImageRepository $imageRepository): JsonResponse
    {
        $products = $productRepository->findBy(["deleted" => 0]);

        if(empty($products)) {
            return new JsonResponse(["msg" => "There are no products yet!"], 404);
        }

        $productResponseDtoArray = [];

        foreach($products as $product)
        {
            $productResponseDto = new ProductResponseDto();
            $productResponseDto->setId($product->getId());
            $productResponseDto->setName($product->getName());
            $productResponseDto->setPrice($product->getPrice());
            $productResponseDto->setCategoryName($product->getCategory()->getName());
            $productResponseDto->setCreatedAt($product->getCreatedAt());
            $productResponseDto->setUpdatedAt($product->getUpdatedAt());

            $image = $imageRepository->findOneBy(["product" => $product]);
            if($image) {
                $productResponseDto->setImageData($image->getImage());
            }

            $productResponseDtoArray[] = $productResponseDto;
        }
        return JsonConverter::jsonResponseConverter($serializer, $productResponseDtoArray);
    }

    public function deleteProduct($id, ProductRepository $productRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $product = $productRepository->find($id);

        if(!$product) {
            return new JsonResponse(["msg" => "Product not found!"], 404);
        }

        $product->setDeleted(1);
        $entityManager->flush();

        return new JsonResponse(["msg" => "Product deleted!"], 200);
    }

    public function editProduct($id, ProductRepository $productRepository, CategoryRepository $categoryRepository,
                                ProductRequestDto $productRequestDto, EntityManagerInterface $entityManager, String $imgData,
                                ImageRepository $imageRepository, User $user): JsonResponse
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

        if(!empty($imgData)) {
            $image = new Image();
            $image->setImage($imgData);
            $image->setProduct($product);
            $image->setUser($user);

            $imageRepository->save($image, true);
        }

        return new JsonResponse(["msg" => "Product updated!"], 200);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function mainPageShowProducts(SerializerInterface $serializer, ProductRepository $productRepository,
                                         ImageRepository     $imageRepository, RateRepository $rateRepository,
                                         RateService         $rateService, int $page): JsonResponse
    {
        $limit = 15;
        if($page > 1) {
            $offset = ($page * $limit) - $limit;
        } else {
            $offset = 0;
        }

        $products = $productRepository->findby(["deleted" => 0],  ["createdAt" => "desc"], $limit, $offset);
        $count = $productRepository->getCount();

        if(empty($products)) {
            return new JsonResponse(["msg" => "There are no products yet!"], 404);
        }

        $productMainPageResponseDtoArray = [];

        foreach($products as $product)
        {
            $productMainPageResponseDto = new ProductMainPageResponseDto();
            $productMainPageResponseDto->setId($product->getId());
            $productMainPageResponseDto->setName($product->getName());
            $productMainPageResponseDto->setPrice($product->getPrice());
            $productMainPageResponseDto->setCategory($product->getCategory()->getName());

            $imgData = $imageRepository->findOneBy(["product" => $product]);
            $productMainPageResponseDto->setImageData($imgData->getImage());

            $rate = $rateService->getProductRate($rateRepository, $productRepository, $product->getId());
            $productMainPageResponseDto->setRate($rate->getRating());

            $productMainPageResponseDtoArray[] = $productMainPageResponseDto;
        }
        $returnedArray = [
            "products" => $productMainPageResponseDtoArray,
            "totalProducts" => $count
        ];
        return JsonConverter::jsonResponseConverter($serializer, $returnedArray);
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