<?php

namespace tests\Functional\Repository;

use App\Entity\Factory\ProductFactory;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Faker\Generator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Resource\Fixture\ProductFixture;
use Tests\Utils\FakerUtilsTrait;

class ProductRepositoryTest extends WebTestCase
{
    use FakerUtilsTrait;

    /**
     * @var ProductRepository|null
     */
    private ?ProductRepository $repository = null;

    /**
     * @var Generator
     */
    private Generator $faker;

    /**
     * @var AbstractDatabaseTool|null
     */
    private ?AbstractDatabaseTool $databaseTool = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository   = static::getContainer()->get(ProductRepository::class);
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function test_product_added_successfully(): void
    {
        $faker = $this->getFaker();

        $name        = $faker->word();
        $description = $faker->sentence();
        $weight      = $faker->randomNumber(5);
        $category    = $faker->word();

        $product = ProductFactory::create($name, $description, $weight, $category);

        $this->repository->save($product);

        /** @var Product|null $productFromDb */
        $productFromDb = $this->repository->findOneBy(['id' => $product->getId()]);

        self::assertNotNull($productFromDb);
        self::assertNotNull($productFromDb?->getId());
        self::assertEquals($name, $productFromDb->getName());
        self::assertEquals($description, $productFromDb->getDescription());
        self::assertEquals($weight, $productFromDb->getWeight());
        self::assertEquals($category, $productFromDb->getCategory());
    }

    public function test_get_all_products_query(): void
    {
        $executor = $this->databaseTool->loadFixtures([ProductFixture::class]);
        /** @var Product $fixtureProduct */
        $fixtureProduct = $executor->getReferenceRepository()->getReference(ProductFixture::REFERENCE);


        $query = $this->repository->getAllProductsQuery();

        $products = $query->setMaxResults(1)->execute();

        self::assertNotEmpty($products);
        self::assertCount(1, $products);

        /** @var Product $product */
        $product = $products[0];

        $this->assertEquals($fixtureProduct->getId(), $product->getId());
    }
}