<?php

namespace Tests\Unit\Utils\XmlReader;

use App\Utils\XML\Exception\XmlParsingException;
use App\Utils\XML\Strategy\ProductsXmlReaderStrategy;
use PHPUnit\Framework\TestCase;
use Tests\Resource\XmlData\BadWeightXml;

class ProductsXmlReaderStrategyTest extends TestCase
{
    public function test_products_xml_reader_strategy()
    {
        $strategy = new ProductsXmlReaderStrategy(1);

        $doc = simplexml_load_string(BadWeightXml::getXML());

        self::expectException(XmlParsingException::class);
        self::expectExceptionMessage("Bad \"weight\" format! Example:");

        $strategy->getWeight($doc->product[0]->weight);
    }
}