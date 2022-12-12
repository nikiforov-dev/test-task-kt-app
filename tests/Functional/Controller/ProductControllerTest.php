<?php

namespace Tests\Functional\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ProductControllerTest extends WebTestCase
{
    public function test_products_responded_successful_result(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/products');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains("Products");
    }
}