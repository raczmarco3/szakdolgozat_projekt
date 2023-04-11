<?php

namespace App\Controller;

use App\Converter\ValidationErrorJsonConverter;
use App\Dto\RequestDto\ProductRequestDto;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

/**
 *  @Route("/api/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/add", methods={"POST"})
     */
    public function addNewProduct(Request $request, SerializerInterface $serializer, ValidatorInterface $validator,
                                  ProductService $productService, CategoryRepository $categoryRepository,
                                  ProductRepository $productRepository, #[CurrentUser] ?User $user): JsonResponse
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

        $productRequestDto = $serializer->deserialize($request->getContent(), ProductRequestDto::class, 'json');

        $errors = $validator->validate($productRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $productService->addNewProduct($productRepository, $categoryRepository, $productRequestDto, $user);
    }

    /**
     * @Route("/all", methods={"GET"})
     */
    public function getProducts(SerializerInterface $serializer, ProductRepository $productRepository,
                                ProductService $productService): JsonResponse
    {
        return $productService->getProducts($serializer, $productRepository);
    }

    /**
     * @Route("/delete/{id}", methods={"DELETE"})
     */
    public function deleteProduct(ProductRepository $productRepository, ProductService $productService, $id): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(!$hasAccess) {
            return new JsonResponse(["msg" => "You don't have the needed permission for this action!"], 423);
        }

        if(!is_numeric($id)) {
            return new JsonResponse(["msg" => "id must be a number!"], 422);
        }

        return $productService->deleteProduct($id, $productRepository);
    }

    /**
     * @Route("/edit/{id}", methods={"PUT"})
     */
    public function editProduct(ProductRepository $productRepository, CategoryRepository $categoryRepository,
                                EntityManagerInterface $entityManager, Request $request, ProductService $productService,
                                SerializerInterface $serializer, ValidatorInterface $validator, $id): JsonResponse
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
        if(!isset($data["id"]) || !is_numeric($data["id"]) || !is_numeric($id)) {
            return new JsonResponse(["msg" => "id must be a number!"], 422);
        } else if($data["id"] != $id) {
            return new JsonResponse(["msg" => "You don't have permission to edit this product!"], 403);
        }

        $productRequestDto = $serializer->deserialize($request->getContent(), ProductRequestDto::class, 'json');

        $errors = $validator->validate($productRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $productService->editProduct($id, $productRepository, $categoryRepository, $productRequestDto, $entityManager);
    }
}


