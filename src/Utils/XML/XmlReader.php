<?php

namespace App\Utils\XML;

use App\Utils\XML\Exception\XmlParsingException;
use App\Utils\XML\Strategy\XmlReaderStrategyInterface;
use Generator;
use SimpleXMLElement;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\FormFactoryInterface;

class XmlReader
{
    /**
     * @var XmlReaderStrategyInterface
     */
    private XmlReaderStrategyInterface $strategy;

    /**
     * @var SimpleXMLElement
     */
    private SimpleXMLElement $xml;

    /**
     * @var int
     */
    private int $length = 0;

    /**
     * @var int
     */
    private int $batchSize = 0;

    /**
     * @var int
     */
    private int $batchesNumber = 0;

    /**
     * @var int
     */
    private int $currentBatchNumber = 0;

    /**
     * @param XmlReaderStrategyInterface $strategy
     * @param string $xmlData
     *
     * @return $this
     *
     * @throws XmlParsingException
     */
    public function readXml(XmlReaderStrategyInterface $strategy, string $xmlData): self
    {
        $this->strategy = $strategy;

        $this->xml       = $this->parseXml($xmlData);
        $this->length    = $this->xml->count();

        $this->batchSize = $this->strategy->getBatchSize();

        $this->batchesNumber = intdiv($this->length, $this->batchSize);

        if ($this->length % $this->batchSize > 0) {
            $this->batchesNumber++;
        }

        return $this;
    }

    /**
     * @return Generator
     */
    public function getResultBatches(): Generator
    {
        for ($this->currentBatchNumber = 0; $this->currentBatchNumber < $this->batchesNumber; $this->currentBatchNumber++) {
            yield $this->strategy->processXmlBatch($this->xml, $this->batchSize, $this->currentBatchNumber);
        }
    }

    /**
     * @param string $xmlData
     * @return SimpleXMLElement
     *
     * @throws XmlParsingException
     */
    private function parseXml(string $xmlData): SimpleXMLElement
    {
        libxml_use_internal_errors(true);

        $doc = simplexml_load_string($xmlData);

        if ($doc === false) {
            $errors = libxml_get_errors();

            libxml_clear_errors();

            throw new XmlParsingException($errors[0]->message);
        }

        return $doc;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @return int
     */
    public function getBatchSize(): int
    {
        return $this->batchSize;
    }

    /**
     * @return int
     */
    public function getCurrentBatchNumber(): int
    {
        return $this->currentBatchNumber;
    }
}