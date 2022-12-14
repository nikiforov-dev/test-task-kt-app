<?php

namespace Tests\Unit\Filter\Product;

use App\Filters\Product\ProductIdFilter;
use PHPUnit\Framework\TestCase;
use Tests\Utils\FakerUtilsTrait;

class ProductIdFilterTest extends TestCase
{
    use FakerUtilsTrait;

    public function test_abstract_filter_is_valid_type()
    {
        $faker = $this->getFaker();

        $filterData = [
            'id' => $faker->words()
        ];

        $productIdFilter = new ProductIdFilter();

        $isValidType = $productIdFilter->isValidType($filterData);

        self::assertFalse($isValidType);
        $isValidType = true;

        $filterData['id'] = new ProductIdFilter(); //Just need some object to test

        $isValidType = $productIdFilter->isValidType($filterData);
        self::assertFalse($isValidType);
    }

    public function test_abstract_filter_check_valid()
    {
        $faker = $this->getFaker();

        $filterData = [
            'id' => new ProductIdFilter() //Just need some object to test
        ];

        $productIdFilter = new ProductIdFilter();

        self::expectException(\LogicException::class);
        self::expectExceptionMessage('Wrong data type');

        $productIdFilter->checkValid($filterData);
    }
}