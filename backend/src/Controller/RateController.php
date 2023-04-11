<?php

namespace App\Controller;

use App\Converter\ValidationErrorJsonConverter;
use App\Dto\RequestDto\RateRequestDto;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\RateRepository;
use App\Service\RateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/rate")
 */
class RateController extends AbstractController
{
    /**
     * @Route("/add", methods={"POST"})
     */
    public function rateProduct(RateRepository $rateRepository, ProductRepository $productRepository,
                                EntityManagerInterface $entityManager, SerializerInterface $serializer,
                                ValidatorInterface $validator, #[CurrentUser] ?User $user, Request $request,
                                RateService $rateService): JsonResponse
    {
        $hasAccess = $this->isGranted('ROLE_USER');
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

        $rateRequestDto = $serializer->deserialize($request->getContent(), RateRequestDto::class, 'json');

        $errors = $validator->validate($rateRequestDto);
        if (count($errors) > 0) {
            return ValidationErrorJsonConverter::convertValidationErrors($errors, $serializer);
        }

        return $rateService->rateProduct($rateRepository, $rateRequestDto, $productRepository, $entityManager, $user);
    }

    /**
     * @Route("/product/{id}", methods={"GET"})
     */
    public function getProductRate(RateRepository $rateRepository, SerializerInterface $serializer,
                                   ProductRepository $productRepository, RateService $rateService, $id): JsonResponse
    {
        if(!is_numeric($id)) {
            return new JsonResponse(["msg" => "id must be a number!"], 422);
        }

        return $rateService->getProductRate($rateRepository, $serializer, $productRepository, $id);
    }

}