<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\CategoryRequestDto;
use App\Dto\ResponseDto\CategoryResponseDto;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CategoryService
{
    public function addCategory(CategoryRepository $categoryRepository, CategoryRequestDto $categoryRequestDto): JsonResponse
    {
        $category = $categoryRepository->findby(["name" => $categoryRequestDto->getName()]);

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

        if($categoryRequestDto->getName() != $category->getName() && $categoryRepository->findBy(["name" => $categoryRequestDto->getName()])) {
            return new JsonResponse(["msg" => "This category already exists!"], 403);
        }

        $category = $this->setCategory($category, $categoryRequestDto);
        $entityManager->flush();

        return new JsonResponse(["msg" => "Category updated!"], 200);
    }

    private function setCategory(Category $category, CategoryRequestDto $categoryRequestDto): Category
    {
        $category->setName($categoryRequestDto->getName());

        return $category;
    }
}