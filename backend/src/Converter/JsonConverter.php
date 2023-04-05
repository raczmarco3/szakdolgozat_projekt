<?php

namespace App\Converter;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonConverter
{
    public static function jsonResponseConverter($serializer, $data, $httpCode = 0): JsonResponse
    {
        $jsonResponse = new JsonResponse();
        $jsonContent = $serializer->serialize($data, 'json');
        $jsonResponse->setContent($jsonContent);

        if($httpCode > 0) {
            $jsonResponse->setStatusCode($httpCode);
        }

        return $jsonResponse;
    }
}