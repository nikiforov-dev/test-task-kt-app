<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Result;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;

class ProductRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param Product $product
     * @param bool $flush
     */
    public function save(Product $product, bool $flush = true): void
    {
        $this->getEntityManager()->persist($product);

        if ($flush) {
           $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Query
     */
    public function getAllProductsQuery(): Query
    {
        return $this->getAllQB()->getQuery();
    }

    /**
     * @return QueryBuilder
     */
    public function getAllQB(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    /**
     * Sequence:
     *   #1 products_import_id
     *   #2 name
     *   #3 description
     *   #4 category
     *   #5 weight
     *
     *
     * @param array $params
     *
     * @return void
     *
     */
    public function insertToProductsFromRawParams(array $params): void
    {
        $fieldsNumber = 5;

        $count = count($params);

        if ($count % $fieldsNumber) {
            throw new LogicException("Wrong number of params! Mean: \"{$count} % {$fieldsNumber} > 0\"");
        }

        $valuesTemplatesCount = intdiv($count, $fieldsNumber);

        if ($valuesTemplatesCount < 1) {
            throw new LogicException("Wrong number of params! Mean: \"{$count} < 5\"");
        }

        $insertQueryTemplate = "INSERT INTO products(products_import_id, name, description, category, weight) VALUES";
        $valuesTemplate      = "(?, ?, ?, ?, ?),";

        $insertQueryTemplate .= str_repeat($valuesTemplate, $valuesTemplatesCount);

        $insertQueryTemplate = rtrim($insertQueryTemplate, ',');
        $insertQueryTemplate .= ';';

        $this
            ->getEntityManager()
            ->createNativeQuery($insertQueryTemplate, new ResultSetMapping())
            ->setParameters($params)
            ->execute()
        ;
    }

    /**
     * @param int|null $id
     *
     * @return Result
     * @throws Exception
     */
    public function getReportByProductsImportId(?int $id): Result
    {
        $select = "SELECT id, name, category, description, weight FROM products WHERE products_import_id = ?;";

        return $this
            ->getEntityManager()
            ->getConnection()
            ->executeQuery($select, [$id])
        ;
    }
}