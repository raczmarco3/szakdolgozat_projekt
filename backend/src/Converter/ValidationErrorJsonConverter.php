<?php

namespace App\Converter;

use Symfony\Component\HttpFoundation\JsonResponse;

class ValidationErrorJsonConverter
{
    public static function convertValidationErrors($errors, $serializer): JsonResponse
    {
        $formattedErrorList = [];

        for ($i = 0; $i < $errors->count(); $i++) {
            $violation = $errors->get($i);
            $formattedErrorList[] = array($violation->getPropertyPath() => $violation->getMessage());
        }
        $msg = ["msg" => $formattedErrorList];
        return JsonConverter::jsonResponseConverter($serializer, $msg, 403);
    }
}