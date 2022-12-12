<?php

namespace tests\Unit\Controller;

use App\Controller\App\ProductController;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    /**
     * @var PaginatorInterface|null
     */
    private ?PaginatorInterface $paginator;

    /**
     * @var ProductRepository|null
     */
    private ?ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paginator         = self::getContainer()->get(PaginatorInterface::class);
        $this->productRepository = self::getContainer()->get(ProductRepository::class);

    }

    public function test_product_controller_constructor(): void
    {
        $productController = new ProductController($this->paginator, $this->productRepository);

        self::assertInstanceOf(ProductController::class, $productController);
    }
}