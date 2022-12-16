<?php

namespace App\Utils\XML\Strategy;

use App\Entity\Factory\ProductFactory;
use App\Entity\Product;
use App\Utils\XML\Exception\XmlParsingException;
use SimpleXMLElement;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\FormFactoryInterface;

class ProductsXmlReaderStrategy implements XmlReaderStrategyInterface
{
    /**
     * @var int
     */
    private int $productsImportId;

    public function __construct(int $productsImportId)
    {
        $this->productsImportId = $productsImportId;
    }

    /**
     * {@inheritdoc}
     */
    public function getBatchSize(): int
    {
        return 5000;
    }

    /**
     * {@inheritdoc}
     *
     * @throws XmlParsingException
     */
    public function processXmlBatch(SimpleXMLElement $xml, int $batchSize, int $currentBatch): array
    {
        $index = $batchSize * $currentBatch;

        $end = $index + $batchSize;

        $results = [];

        for (; $index < $end && $index < $xml->count(); $index++) {
            $this->createRawValues($xml->product[$index], $results);
        }

        return $results;
    }

    /**
     * Sequence:
     *    #1 products_import_id
     *    #2 name
     *    #3 description
     *    #4 category
     *    #5 weight
     *
     * @throws XmlParsingException
     */
    public function createRawValues(SimpleXMLElement $element, array &$results): void
    {
        $results[] = $this->productsImportId;
        $results[] = (string) $element->name;
        $results[] = (string) $element->description;
        $results[] = (string) $element->category;
        $results[] = $this->getWeight($element->weight);
    }

    /**
     * @param SimpleXMLElement $weight
     *
     * @return int
     *
     * @throws XmlParsingException
     */
    public function getWeight(SimpleXMLElement $weight): int
    {
        $weightMatch = [];

        if (preg_match("/(\d+) kg/", $weight, $weightMatch)) {
            $weight = ((int) $weightMatch[0]) * 1000;
        } else if (preg_match("/(\d+) g/", $weight, $weightMatch)) {
            $weight = (int) $weightMatch[0];
        } else {
            throw new XmlParsingException("Bad \"weight\" format! Example: {$weight}");
        }

        return $weight;
    }
}