<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\CategoryRequestDto;
use App\Dto\ResponseDto\CategoryResponseDto;
use App\Dto\ResponseDto\ProductMainPageResponseDto;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryService
{
    public function addCategory(CategoryRepository $categoryRepository, CategoryRequestDto $categoryRequestDto): JsonResponse
    {
        $category = $categoryRepository->findOneby(["name" => $categoryRequestDto->getName()]);

        if($category) {
            return new JsonResponse(["msg" => "This category already exists!"], 403);
        }

        $category = new Category();
        $category = $this->setCategory($category, $categoryRequestDto);

        $categoryRepository->save($category, true);
        return new JsonResponse(["msg" => "Category created!"], 201);
    }

    public function getCategories(SerializerInterface $serializer, CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();

        if(empty($categories)) {
            return new JsonResponse(["msg" => "There are no categories yet!"], 404);
        }

        $categoryResponseDtoArray = [];

        foreach ($categories as $category)
        {
            $categoryResponseDto = new CategoryResponseDto();
            $categoryResponseDto->setId($category->getId());
            $categoryResponseDto->setName($category->getName());

            $categoryResponseDtoArray[] = $categoryResponseDto;
        }
        return JsonConverter::jsonResponseConverter($serializer, $categoryResponseDtoArray);
    }

    public function deleteCategory($id, CategoryRepository $categoryRepository): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if(!$category) {
            return new JsonResponse(["msg" => "Category not found!"], 404);
        }

        $categoryRepository->remove($category, true);
        return new JsonResponse(["msg" => "Category deleted!"], 200);
    }

    public function editCategory($id, CategoryRequestDto $categoryRequestDto, CategoryRepository $categoryRepository,
                                 EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $categoryRepository->find($id);

        if(!$category) {
            return new JsonResponse(["msg" => "Category not found!"], 404);
        }

        if($categoryRequestDto->getName() != $category->getName() && $categoryRepository->findOneBy(["name" => $categoryRequestDto->getName()])) {
            return new JsonResponse(["msg" => "This category already exists!"], 403);
        }

        $category = $this->setCategory($category, $categoryRequestDto);
        $entityManager->flush();

        return new JsonResponse(["msg" => "Category updated!"], 200);
    }

    public function getRelatedProducts(ProductRepository $productRepository, CategoryRepository $categoryRepository,
                                       $categoryName, ImageRepository $imageRepository, SerializerInterface $serializer,
                                       RateRepository $rateRepository, RateService $rateService, int $productId): JsonResponse
    {
        $category = $categoryRepository->findOneBy(["name" => $categoryName]);
        if(!$category) {
            return new JsonResponse(["msg" => "Category not found!"], 404);
        }

        $products = $productRepository->findBy(["category" => $category], ["createdAt" => "desc"], 5, 0);
        if(empty($products)) {
            return new JsonResponse(["msg" => "There are related products yet!"], 404);
        }

        $productMainPageResponseDtoArray = [];

        foreach($products as $product)
        {
            if($product->getId() != $productId) {
                $productMainPageResponseDto = new ProductMainPageResponseDto();
                $productMainPageResponseDto->setId($product->getId());
                $productMainPageResponseDto->setName($product->getName());
                $productMainPageResponseDto->setPrice($product->getPrice());
                $productMainPageResponseDto->setCategory($product->getCategory()->getName());

                $imgData = $imageRepository->findOneBy(["product" => $product]);
                $productMainPageResponseDto->setImage($imgData->getImage());

                $rate = $rateService->getProductRate($rateRepository, $productRepository, $product->getId());
                $productMainPageResponseDto->setRate($rate->getRating());

                $productMainPageResponseDtoArray[] = $productMainPageResponseDto;
            }
        }
        return JsonConverter::jsonResponseConverter($serializer, $productMainPageResponseDtoArray);
    }

    private function setCategory(Category $category, CategoryRequestDto $categoryRequestDto): Category
    {
        $category->setName($categoryRequestDto->getName());

        return $category;
    }
}