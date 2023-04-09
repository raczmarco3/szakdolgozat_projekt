<?php

namespace App\Controller;

use App\Converter\ValidationErrorJsonConverter;
use App\Dto\RequestDto\ProductRequestDto;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *  @Route("/api/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/add", methods={"POST"})
     */
    public function addNewProduct(Request $request, SerializerInterface $serializer, ValidatorInterface $validator,
                                  ProductService $productService, CategoryRepository $categoryRepository, ProductRepository $productRepository): JsonResponse
    {
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

        return $productService->addNewProduct($productRepository, $categoryRepository, $productRequestDto);
    }
}