<?php

namespace Tests\Unit\Repository;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use LogicException;

class ProductRepositoryTest extends WebTestCase
{
    /**
     * @var ProductRepository|null
     */
    private ?ProductRepository $productsRepository;

    protected function setUp(): void
    {
        $this->productsRepository = self::getContainer()->get(ProductRepository::class);
    }

    public function test_products_repository__insert_to_products_from_raw_params__bad_div()
    {
        $array = [1, 2, 3, 4, 5, 6];

        self::expectException(LogicException::class);

        $this->productsRepository->insertToProductsFromRawParams($array);

    }

    public function test_products_repository__insert_to_products_from_raw_params__bad_mod()
    {
        $array = [];

        self::expectException(LogicException::class);

        $this->productsRepository->insertToProductsFromRawParams($array);

    }
}