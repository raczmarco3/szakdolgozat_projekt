<?php

namespace App\Controller;

use App\Converter\ValidationErrorJsonConverter;
use App\Dto\RequestDto\CategoryRequestDto;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Repository\RateRepository;
use App\Service\CategoryService;
use App\Service\RateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route ("/add", methods={"POST"})
     */
    public function addCategory(CategoryRepository $categoryRepository, SerializerInterface $serializer,
                                ValidatorInterface $validator, Request $request, #[CurrentUser] ?User $user): JsonResponse
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

        $categoryRequestDto = $serializer->deserialize($request->getContent(), CategoryRequestDto::class, 'json');

        $errors = $validator->validate($categoryRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        $categoryService = new CategoryService();
        return $categoryService->addCategory($categoryRepository, $categoryRequestDto);
    }

    /**
     * @Route("/all", methods={"GET"})
     */
    public function getCategories(SerializerInterface $serializer, CategoryRepository $categoryRepository): JsonResponse
    {
        $categoryService = new CategoryService();
        return $categoryService->getCategories($serializer, $categoryRepository);
    }

    /**
     * @Route("/delete/{id}", methods={"DELETE"})
     */
    public function deleteCategory(CategoryRepository $categoryRepository, $id): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        if(!is_numeric($id)) {
            return new JsonResponse(["msg" => "id must be a number!"], 422);
        }

        $categoryService = new CategoryService();
        return $categoryService->deleteCategory($id, $categoryRepository);
    }

    /**
     * @Route("/edit/{id}", methods={"PUT"})
     */
    public function editCategory(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager,
                                 Request $request, SerializerInterface $serializer, ValidatorInterface $validator,
                                 $id): JsonResponse
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

        $data = json_decode($request->getContent(), true);
        if(!isset($data["id"]) || !is_numeric($data["id"])) {
            return new JsonResponse(["msg" => "id must be a number!"], 422);
        } else if($data["id"] != $id) {
            return new JsonResponse(["msg" => "You don't have permission to edit this category!"], 403);
        }

        $categoryRequestDto = $serializer->deserialize($request->getContent(), CategoryRequestDto::class, 'json');

        $errors = $validator->validate($categoryRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        $categoryService = new CategoryService();
        return $categoryService->editCategory($id, $categoryRequestDto, $categoryRepository, $entityManager);
    }

    /**
     * @Route("/related/{categoryName}/{productId}", methods={"GET"})
     */
    public function getRelatedProducts(ProductRepository $productRepository, CategoryRepository $categoryRepository,
                                       $categoryName, ImageRepository $imageRepository, SerializerInterface $serializer,
                                       RateRepository $rateRepository, RateService $rateService,
                                       CategoryService $categoryService, $productId): JsonResponse
    {
        return $categoryService->getRelatedProducts($productRepository, $categoryRepository,
                                       $categoryName, $imageRepository, $serializer,
                                       $rateRepository, $rateService, $productId);
    }

}