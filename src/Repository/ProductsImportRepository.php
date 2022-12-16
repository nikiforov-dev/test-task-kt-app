<?php

namespace App\Repository;

use App\Entity\Factory\ProductsImportFactory;
use App\Entity\ProductsImport;
use App\Utils\FileUploader\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductsImportRepository extends ServiceEntityRepository
{
    /**
     * @var FileUploader
     */
    private FileUploader $fileUploader;

    /**
     * {@inheritdoc}
     */
    public function __construct(ManagerRegistry $registry, FileUploader $fileUploader)
    {
        parent::__construct($registry, ProductsImport::class);

        $this->fileUploader = $fileUploader;
    }

    /**
     * @return QueryBuilder
     */
    public function getAllQB(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    /**
     * @param UploadedFile $uploadedFile
     *
     * @return string
     */
    public function createProductsImportWithFileUpload(UploadedFile $uploadedFile): string
    {
        $this->getEntityManager()->beginTransaction();

        $uploadedFilePath = $this->fileUploader->upload($uploadedFile);

        $productsImport = ProductsImportFactory::create($uploadedFilePath);

        $this->getEntityManager()->persist($productsImport);
        $this->getEntityManager()->flush();

        $this->getEntityManager()->commit();

        return $uploadedFilePath;
    }

    /**
     * @return ProductsImport|null
     *
     * @throws NonUniqueResultException
     */
    public function getFirstUnprocessedProductsImport(): ProductsImport|null
    {
        $qb = $this->getAllQB();

        $alias = $qb->getRootAliases()[0];

        return $qb->andWhere("{$alias}.status = '" . ProductsImport::UNPROCESSED . "'")
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param ProductsImport $productsImport
     */
    public function save(ProductsImport $productsImport): void
    {
        $this->getEntityManager()->persist($productsImport);
        $this->getEntityManager()->flush();
    }
}