<?php

namespace tests\Functional\Repository;

use App\Entity\Factory\ProductFactory;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductRepositoryTest extends WebTestCase
{
    /**
     * @var ProductRepository
     */
    private object $repository;

    private Generator $faker;

    public function setUp(): void
    {
        parent::setUp();

        $kernel = $this::getKernelClass();

        $this->repository = static::getContainer()->get(ProductRepository::class);
        $this->faker      = Factory::create();
    }

    public function test_product_added_successfully(): void
    {
        $name        = $this->faker->word();
        $description = $this->faker->sentence();
        $weight      = $this->faker->randomNumber(5);
        $category    = $this->faker->word();

        $product = ProductFactory::create($name, $description, $weight, $category);

        $this->repository->save($product);

        /** @var Product|null $productFromDb */
        $productFromDb = $this->repository->findOneBy(['name' => $name]);

        self::assertNotNull($productFromDb);
        self::assertNotNull($productFromDb?->getId());
        self::assertEquals($name, $productFromDb->getName());
        self::assertEquals($description, $productFromDb->getDescription());
        self::assertEquals($weight, $productFromDb->getWeight());
        self::assertEquals($category, $productFromDb->getCategory());
    }
}