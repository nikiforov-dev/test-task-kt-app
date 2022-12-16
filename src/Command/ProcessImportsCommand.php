<?php

namespace App\Command;

use App\Entity\ProductsImport;
use App\Repository\ProductRepository;
use App\Repository\ProductsImportRepository;
use App\Utils\XML\Exception\XmlParsingException;
use App\Utils\XML\Strategy\ProductsXmlReaderStrategy;
use App\Utils\XML\XmlReader;
use App\DependencyInjection\Trait\LoggerDependencyInjectionTrait;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Throwable;

class ProcessImportsCommand extends Command
{
    use LoggerDependencyInjectionTrait;

    /**
     * @var XmlReader
     */
    private XmlReader $xmlReader;

    /**
     * @var ProductsImportRepository
     */
    private ProductsImportRepository $productsImportRepository;

    /**
     * @var string
     */
    private string $targetDirectory;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @param XmlReader $xmlReader
     * @param ProductsImportRepository $productsImportRepository
     * @param ProductRepository $productRepository
     * @param string $targetDirectory
     */
    public function __construct(
        XmlReader $xmlReader,
        ProductsImportRepository $productsImportRepository,
        ProductRepository $productRepository,
        string $targetDirectory
    )
    {
        parent::__construct();

        $this->xmlReader                = $xmlReader;
        $this->productsImportRepository = $productsImportRepository;
        $this->productRepository        = $productRepository;

        $this->targetDirectory = $targetDirectory;
    }

    protected function configure()
    {
        $this->setName('app:process:imports');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws NonUniqueResultException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $productImport = $this->productsImportRepository->getFirstUnprocessedProductsImport();

        if (is_null($productImport)) {
            return Command::SUCCESS;
        }

        try {
            $this->process($productImport);
            $productImport->setStatus(ProductsImport::FINISHED);
        } catch (Throwable $e) {
            $this->logger->error("Can't process ProductsImport: {$e->getMessage()}");

            $productImport
                ->setStatus(ProductsImport::ERROR)
                ->setError($e->getMessage())
            ;
        } finally {
            $this->createReport($productImport);
            $this->productsImportRepository->save($productImport);

        }

        return Command::SUCCESS;
    }

    /**
     * @param ProductsImport $productsImport
     * @return void
     * @throws XmlParsingException
     */
    private function process(ProductsImport $productsImport): void
    {
        $productsImport->setStatus(ProductsImport::PROCESSING);

        $fullFilePath = $productsImport->getImportXmlFile();

        $fileContent = file_get_contents($fullFilePath);

        $this->xmlReader->readXml(new ProductsXmlReaderStrategy($productsImport->getId()), $fileContent);
        $productsImport->setCount($this->xmlReader->getLength());

        foreach ($this->xmlReader->getResultBatches() as $productsBatch) {
            $this->productRepository->insertToProductsFromRawParams($productsBatch);

            $alreadyLoaded = $this->xmlReader->getBatchSize() * ($this->xmlReader->getCurrentBatchNumber() + 1);

            if ($alreadyLoaded > $productsImport->getCount()) {
                $alreadyLoaded = $productsImport->getCount();
            }

            $productsImport->setAlreadyLoaded($alreadyLoaded);
            $this->productsImportRepository->save($productsImport);
        }
    }

    /**
     * @param ProductsImport $productsImport
     * @throws Exception
     */
    private function createReport(ProductsImport $productsImport): void
    {
        $results = $this->productRepository->getReportByProductsImportId($productsImport->getId());

        $reportPath = "{$this->targetDirectory}/Report-" . uniqid() . ".csv";

        $productsImport->setReportCsvFile($reportPath);

        $fileHandler = fopen($reportPath, 'w+');

        foreach($results->fetchAllAssociative() as $result) {
            fputcsv($fileHandler, $result);
        }

        $productsImport->setReportCsvFile(substr($productsImport->getReportCsvFile(), 11));

        fclose($fileHandler);
    }
}