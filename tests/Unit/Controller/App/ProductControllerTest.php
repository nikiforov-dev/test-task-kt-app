<?php

namespace tests\Unit\Controller\App;

use App\Controller\App\ProductController;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\FormFactoryInterface;

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

    /**
     * @var FormFactoryInterface|null
     */
    private ?FormFactoryInterface $formFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paginator         = self::getContainer()->get(PaginatorInterface::class);
        $this->productRepository = self::getContainer()->get(ProductRepository::class);
        $this->formFactory       = self::getContainer()->get(FormFactoryInterface::class);

    }

    public function test_product_controller_constructor(): void
    {
        $productController = new ProductController(
            $this->formFactory,
            $this->paginator,
            $this->productRepository
        );

        self::assertInstanceOf(ProductController::class, $productController);
    }
}