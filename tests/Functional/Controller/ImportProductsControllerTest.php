<?php

namespace Tests\Functional\Controller;

use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Tests\Resource\XmlData\GoodXml;
use Tests\Utils\FakerUtilsTrait;

class ImportProductsControllerTest extends WebTestCase
{
    use FakerUtilsTrait;

    private const UPLOADS_PATH = "/app/public/uploads/";

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * @var Generator
     */
    private Generator $faker;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->faker = $this->getFaker();
    }

    public function test_import_products_action(): void
    {
        $this->client->request(Request::METHOD_GET, '/import');

        $this->assertResponseIsSuccessful();

        $this->assertPageTitleContains('Import');
    }

    public function test_upload_action(): void
    {
        $temp = tmpfile();

        fwrite($temp, GoodXml::getXML());

        $filePath = stream_get_meta_data($temp)['uri'];

        $uploadedFile = new UploadedFile($filePath, 'import-example.xml', 'text/xml');

        $this->client->request(Request::METHOD_POST, '/upload', [],
            ['import_file' => $uploadedFile],
        );

        self::assertResponseIsSuccessful();

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey("status", $jsonResponse);
        self::assertEquals("Uploaded", $jsonResponse["status"]);
        self::assertArrayHasKey("file_name", $jsonResponse);

        unlink($jsonResponse["file_name"]);
    }

    public function test_upload_without_file(): void
    {
        $this->client->request(Request::METHOD_POST, '/upload');

        $jsonResponse = json_decode($this->client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(400);
        self::assertArrayHasKey('error', $jsonResponse);
        self::assertEquals("Import file is missed!", $jsonResponse['error']);
    }

}