<?php

namespace App\Entity\Factory;

use App\Entity\ProductsImport;

class ProductsImportFactory
{
    /**
     * @param string $xmlFileName
     *
     * @return ProductsImport
     */
    public static function create(string $xmlFileName): ProductsImport
    {
        return (new ProductsImport())
            ->setId(null)
            ->setImportXmlFile($xmlFileName)
            ->setReportCsvFile(null)
            ->setProcessed(null)
            ->setCreatedAt(null)
        ;
    }
}