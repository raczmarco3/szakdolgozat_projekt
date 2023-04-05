<?php

namespace App\Converter;

use App\Dto\ResponseDto\ProductPageResponseDto;
use App\Entity\Image;
use App\Entity\Product;

class ProductPageConverter
{
    public static function productPageResonseDtoConverter(Product $product, Image $image): ProductPageResponseDto
    {
        $productPageResponseDto = new ProductPageResponseDto();

        $productPageResponseDto->setName($product->getName());
        $productPageResponseDto->setPrice($product->getPrice());
        $productPageResponseDto->setCategoryName($product->getCategory()->getName());
        $productPageResponseDto->setImage($image->getImage());

        return $productPageResponseDto;
    }
}