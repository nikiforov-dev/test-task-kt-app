<?php

namespace Tests\Unit\Entity;

use App\Entity\Factory\ProductsImportFactory;
use App\Entity\ProductsImport;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class ProductsImportTest extends TestCase
{
    /**
     * @var Generator
     */
    private Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    public function test_create_product_import()
    {
        $id          = $this->faker->randomNumber(5);
        $xmlFileName = $this->faker->word();
        $count       = $this->faker->randomNumber(5);
        $alreadyLoaded = $this->faker->randomNumber(5);
        $csvFileName = $this->faker->word();
        $error       = $this->faker->sentence();
        $status      = ProductsImport::UNPROCESSED;

        $productsImport = ProductsImportFactory::create($xmlFileName);
        $productsImport
            ->setId($id)
            ->setCount($count)
            ->setAlreadyLoaded($alreadyLoaded)
            ->setReportCsvFile($csvFileName)
            ->setError($error)
            ->setStatus($status)
        ;


        self::assertEquals($id, $productsImport->getId());
        self::assertEquals($xmlFileName, $productsImport->getImportXmlFile());
        self::assertEquals($count, $productsImport->getCount());
        self::assertEquals($alreadyLoaded, $productsImport->getAlreadyLoaded());
        self::assertEquals($csvFileName, $productsImport->getReportCsvFile());
        self::assertEquals($error, $productsImport->getError());
        self::assertEquals($status, $productsImport->getStatus());
    }

    public function test_products_import_created_at_methods(): void
    {
        $prductsImport = ProductsImportFactory::create($this->faker->sentence());

        $createdAtNull = $prductsImport->getCreatedAt();

        $prductsImport->prePersist();

        $createdAt = $prductsImport->getCreatedAt();

        self::assertNotEquals($createdAtNull, $createdAt);
    }
}