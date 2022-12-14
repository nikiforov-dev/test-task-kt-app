<?php

namespace Tests\Functional\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Utils\FakerUtilsTrait;
use function PHPUnit\Framework\assertEquals;

class ProductControllerTest extends WebTestCase
{
    use FakerUtilsTrait;

    public function test_products_responded_successful_result(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/products');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains("Products");
    }

    public function test_products_filters_saved(): void
    {
        $faker = $this->getFaker();

        $id          = $faker->randomNumber(5);
        $name        = $faker->word();
        $category    = $faker->word();
        $description = $faker->word();
        $fromWeight  = $faker->randomNumber(5);
        $toWeight    = $faker->randomNumber(5);
        $orderBy     = 'id';
        $direction   = 'ASC';

        $client = self::createClient();

        $response = $client->request(Request::METHOD_GET, "/products?" .
            "id={$id}&"                   .
            "name={$name}&"               .
            "category={$category}&"       .
            "description={$description}&" .
            "fromWeight={$fromWeight}&"   .
            "toWeight={$toWeight}&"       .
            "orderBy={$orderBy}&"         .
            "direction={$direction}")
        ;

        self::assertResponseIsSuccessful();

        $responseId          = $response->filter('#id')->attr('value');
        $responseName        = $response->filter('#name')->attr('value');
        $responseCategory    = $response->filter('#category')->attr('value');
        $responseDescription = $response->filter('#description')->attr('value');
        $responseFromWeight  = $response->filter('#fromweight')->attr('value');
        $responseToWeight    = $response->filter('#toweight')->attr('value');

        self::assertEquals($id, $responseId, 'id not equal');
        self::assertEquals($name, $responseName, 'name not equal');
        self::assertEquals($category, $responseCategory, 'category not equal');
        self::assertEquals($description, $responseDescription, 'description not equal');
        self::assertEquals($fromWeight, $responseFromWeight, 'fromWeight not equal');
        self::assertEquals($toWeight, $responseToWeight, 'toWeight not equal');

        $responseOrderBy   = $response->filter('#orderby > option:selected')->attr('value');
        $responseDirection = $response->filter('#direction > option:selected')->attr('value');

        self::assertEquals($orderBy, $responseOrderBy);
        self::assertEquals($direction, $responseDirection);
    }
}