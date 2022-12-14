<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

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
}