<?php

namespace Tests\Unit\Entity;

use App\Entity\Factory\ProductFactory;
use App\Entity\Factory\ProductsImportFactory;
use App\Entity\Product;
use App\Entity\ProductsImport;
use PHPUnit\Framework\TestCase;

class ProductsTest extends TestCase
{
    public function test_product_get_products_import()
    {
        $product = new Product();
        $product->setProductsImport(new ProductsImport());

        self::assertInstanceOf(ProductsImport::class, $product->getProductsImport());
    }
}