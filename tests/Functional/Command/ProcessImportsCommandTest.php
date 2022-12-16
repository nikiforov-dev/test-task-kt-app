<?php

namespace Tests\Functional\Command;

use App\Command\ProcessImportsCommand;
use App\Entity\Factory\ProductsImportFactory;
use App\Entity\ProductsImport;
use App\Repository\ProductRepository;
use App\Repository\ProductsImportRepository;
use App\Utils\XML\XmlReader;
use Doctrine\ORM\NonUniqueResultException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\Resource\XmlData\BadXml;
use Tests\Resource\XmlData\GoodXml;

class ProcessImportsCommandTest extends WebTestCase
{
    private const TARGET_DIRECTORY = "/app/public/uploads";

    /**
     * @var XmlReader|null
     */
    private ?XmlReader $xmlReader;

    /**
     * @var ProductsImportRepository|null
     */
    private ?ProductsImportRepository $productsImportRepository;

    /**
     * @var ProductRepository|null
     */
    private ?ProductRepository $productsRepository;

    /**
     * @var string
     */
    private string $targetDirectory;

    /**
     * @var LoggerInterface|null
     */
    private ?LoggerInterface $logger;

    protected function setUp(): void
    {
        $container = self::getContainer();

        $this->xmlReader                = $container->get(XmlReader::class);
        $this->productsImportRepository = $container->get(ProductsImportRepository::class);
        $this->productsRepository       = $container->get(ProductRepository::class);
        $this->logger                   = $container->get(LoggerInterface::class);
        $this->targetDirectory          = self::TARGET_DIRECTORY;
    }

    public function test_process_imports_command_and_no_imports()
    {
        self::assertEquals(Command::SUCCESS, $this->runCommand());
    }

    public function test_process_imports_command__good_import_objects_exists()
    {
        $filePath = $this->createAndUploadProductsImport(GoodXml::getXML());

        $this->runCommand();
        unset($filePath);

        $result = $this->productsRepository->getAllQB()->getQuery()->execute();

        /** +1 is for permanent fixture product*/
        self::assertCount(GoodXml::getProductsCount() + 1, $result);
    }

    public function test_process_imports_command__bad_import_objects_exists()
    {
        $filePath = $this->createAndUploadProductsImport(BadXml::getXML());

        $this->runCommand();
        unset($filePath);

        $result = $this->productsRepository
            ->getAllQB()
            ->getQuery()
            ->execute()
        ;

        /** @var ProductsImport $productsImport */
        $productsImports = $this->productsImportRepository
            ->getAllQB()
            ->andWhere("p.status = '" . ProductsImport::ERROR . "'")
            ->getQuery()
            ->execute()
        ;

        foreach ($productsImports as $productsImport) {
            if ($productsImport->getStatus() !== ProductsImport::ERROR) {
                continue;
            }

            self::assertEquals(ProductsImport::ERROR, $productsImport->getStatus());
            self::assertNotEmpty($productsImport->getError());
        }
    }

    public function test_process_imports_command__corrupted_path_to_import_file(): void
    {
        $importObject = ProductsImportFactory::create('bad_name');

        $this->productsImportRepository->save($importObject);

        self::assertEquals(Command::SUCCESS, $this->runCommand());

        self::assertNotEmpty($importObject->getError());
        self::assertEquals(ProductsImport::ERROR, $importObject->getStatus());
    }

    /**
     * @return int
     *
     * @throws NonUniqueResultException
     */
    private function runCommand(): int
    {
        $command = new ProcessImportsCommand($this->xmlReader, $this->productsImportRepository, $this->productsRepository, $this->targetDirectory);
        $command->setLogger($this->logger);


        $input  = new ArgvInput();
        $output = new BufferedOutput(OutputInterface::VERBOSITY_QUIET, false);

        return $command->execute($input, $output);
    }

    private function createAndUploadProductsImport(string $xml): string
    {
        $temp = tmpfile();

        fwrite($temp, $xml);

        $filePath = stream_get_meta_data($temp)['uri'];

        $uploadedFile = new UploadedFile($filePath, 'import-example.xml', 'text/xml', UPLOAD_ERR_OK, true);

        return $this->productsImportRepository->createProductsImportWithFileUpload($uploadedFile);
    }
}