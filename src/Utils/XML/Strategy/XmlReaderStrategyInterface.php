<?php

namespace App\Utils\XML\Strategy;

use SimpleXMLElement;

interface XmlReaderStrategyInterface
{
    /**
     * @param SimpleXMLElement $xml
     * @param int $batchSize
     * @param int $currentBatch
     *
     * @return array
     */
    public function processXmlBatch(SimpleXMLElement $xml, int $batchSize, int $currentBatch): array;

    /**
     * @return int
     */
    public function getBatchSize(): int;
}