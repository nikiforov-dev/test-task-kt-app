<?php

namespace Tests\Unit\Controller\App\DTO;

use App\Controller\App\DTO\ProductsQueryDTO;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Tests\Utils\FakerUtilsTrait;
use function PHPUnit\Framework\assertEmpty;

class ProductQueryDTOTest extends TestCase
{
    use FakerUtilsTrait;

    public function test_product_query_dto__setters_and_getters(): void
    {
        $fakeData = $this->createFullFakeData();

        $productQueryDTO = (new ProductsQueryDTO())
            ->setId($fakeData['id'])
            ->setName($fakeData['name'])
            ->setCategory($fakeData['category'])
            ->setDescription($fakeData['description'])
            ->setFromWeight($fakeData['fromWeight'])
            ->setToWeight($fakeData['toWeight'])
            ->setOrderBy($fakeData['orderBy'])
            ->setDirection($fakeData['direction'])
        ;

        self::assertEquals($fakeData['id'], $productQueryDTO->getId());
        self::assertEquals($fakeData['name'], $productQueryDTO->getName());
        self::assertEquals($fakeData['category'], $productQueryDTO->getCategory());
        self::assertEquals($fakeData['description'], $productQueryDTO->getDescription());
        self::assertEquals($fakeData['fromWeight'], $productQueryDTO->getFromWeight());
        self::assertEquals($fakeData['toWeight'], $productQueryDTO->getToWeight());
        self::assertEquals($fakeData['orderBy'], $productQueryDTO->getOrderBy());
        self::assertEquals($fakeData['direction'], $productQueryDTO->getDirection());
    }

    public function test_product_query_dto__bad_input_turns_to_null()
    {
        $faker = $this->getFaker();

        $productQueryDTO = new ProductsQueryDTO();

        $productQueryDTO
            ->setId($faker->word())
            ->setFromWeight($faker->word())
            ->setToWeight($faker->word())
            ->setOrderBy('fake')
            ->setDirection('fake')
        ;

        self::assertEquals(null, $productQueryDTO->getId());
        self::assertEquals(null, $productQueryDTO->getFromWeight());
        self::assertEquals(null, $productQueryDTO->getToWeight());
        self::assertEquals(null, $productQueryDTO->getOrderBy());
        self::assertEquals(null, $productQueryDTO->getDirection());
    }

    public function test_product_query_dto__calculate_filter_data()
    {
        $fakeData = $this->createFullFakeData();

        $productQueryDTO = $this->createFullFakeProductsQueryDTO($fakeData);

        $filterData = $productQueryDTO->calculateFilterData();

        self::assertArrayHasKey('id', $filterData);
        self::assertArrayHasKey('name', $filterData);
        self::assertArrayHasKey('category', $filterData);
        self::assertArrayHasKey('description', $filterData);
        self::assertArrayHasKey('fromWeight', $filterData);
        self::assertArrayHasKey('toWeight', $filterData);

        self::assertArrayNotHasKey('orderBy', $filterData);
        self::assertArrayNotHasKey('direction', $filterData);
    }

    public function test_product_query_dto__calculate_order_by_data()
    {
        $fakeData = $this->createFullFakeData();

        $productQueryDTO = $this->createFullFakeProductsQueryDTO($fakeData);

        $orderByData = $productQueryDTO->calculateOrderByData();

        self::assertArrayHasKey('orderBy', $orderByData);
        self::assertArrayHasKey('direction', $orderByData);

        self::assertArrayNotHasKey('id', $orderByData);
        self::assertArrayNotHasKey('name', $orderByData);
        self::assertArrayNotHasKey('category', $orderByData);
        self::assertArrayNotHasKey('description', $orderByData);
        self::assertArrayNotHasKey('fromWeight', $orderByData);
        self::assertArrayNotHasKey('toWeight', $orderByData);
    }

    public function test_product_query_dto__calculate_order_by_data__check_exceptions()
    {
        $fakeData = $this->createFullFakeData();
        $productQueryDTO = $this->createFullFakeProductsQueryDTO($fakeData);

        $productQueryDTO->setDirection(null);
        $orderByData = $productQueryDTO->calculateOrderByData();

        self::assertArrayHasKey('direction', $orderByData);
        self::assertEquals('DESC', $orderByData['direction']);

        $productQueryDTO->setDirection('bad_direction');
        $orderByData = $productQueryDTO->calculateOrderByData();

        self::assertArrayNotHasKey('direction', $orderByData);
        self::assertEmpty($orderByData);

        $productQueryDTO->setDirection('ASC');
        $orderByData = $productQueryDTO->calculateOrderByData();

        self::assertArrayHasKey('direction', $orderByData);
        self::assertEquals('ASC', $orderByData['direction']);

        $productQueryDTO->setOrderBy('property_that_doesnt_exist');
        $orderByData = $productQueryDTO->calculateOrderByData();

        assertEmpty($orderByData);
    }

    /**
     * @return array
     */
    private function createFullFakeData(): array
    {
        $faker = $this->getFaker();

        return [
            'id'          => $faker->randomNumber(5),
            'name'        => $faker->word(),
            'category'    => $faker->word(),
            'description' => $faker->sentence(),
            'fromWeight'  => $faker->randomNumber(5),
            'toWeight'    => $faker->randomNumber(5),
            'orderBy'     => 'id',
            'direction'   => 'DESC',
        ];
    }

    /**
     * @param array $fakeData
     *
     * @return ProductsQueryDTO
     */
    private function createFullFakeProductsQueryDTO(array $fakeData): ProductsQueryDTO
    {
        return (new ProductsQueryDTO())
            ->setId($fakeData['id'])
            ->setName($fakeData['name'])
            ->setCategory($fakeData['category'])
            ->setDescription($fakeData['description'])
            ->setFromWeight($fakeData['fromWeight'])
            ->setToWeight($fakeData['toWeight'])
            ->setOrderBy($fakeData['orderBy'])
            ->setDirection($fakeData['direction'])
        ;
    }
}