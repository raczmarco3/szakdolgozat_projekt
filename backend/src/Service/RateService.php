<?php

namespace App\Service;

use App\Converter\JsonConverter;
use App\Dto\RequestDto\RateRequestDto;
use App\Dto\ResponseDto\RateResponseDto;
use App\Entity\Rate;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class RateService
{
    public function rateProduct(RateRepository $rateRepository, RateRequestDto $rateRequestDto,
                               ProductRepository $productRepository, EntityManagerInterface $entityManager, User $user): JsonResponse
    {
        $product = $productRepository->find($rateRequestDto->getProductId());
        if(!$product) {
            return new JsonResponse(["msg" => "Product not found!"], 404);
        }

        $rate = $rateRepository->findOneBy([
            "product" => $product,
            "user" => $user
        ]);

        $rateExisted = true;

        if(!$rate) {
            $rate = new Rate();
            $rate->setUser($user);
            $rate->setProduct($product);
            $rateExisted = false;
        }

        $rate->setRating($rateRequestDto->getRating());

        if($rateExisted) {
            $entityManager->flush();
        } else {
            $rateRepository->save($rate, true);
        }

        return new JsonResponse(["msg" => "Thank you for your feedback!"], 200);
    }

    public function getProductRate(RateRepository $rateRepository, SerializerInterface $serializer,
                                   ProductRepository $productRepository, int $id): JsonResponse
    {
        $product = $productRepository->find($id);
        if(!$product) {
            return new JsonResponse(["msg" => "Product not found!"], 404);
        }

        $rates = $rateRepository->findBy(["product" => $product]);
        $rating = 0;
        $count = 0;

        foreach ($rates as $rate)
        {
            $rating = $rating + $rate->getRating();
            $count++;
        }

        $rating = $rating / $count;

        $rateResponseDto = new RateResponseDto();
        $rateResponseDto->setRating($rating);
        $rateResponseDto->setProductId($product->getId());

        return JsonConverter::jsonResponseConverter($serializer, $rateResponseDto);
    }
}