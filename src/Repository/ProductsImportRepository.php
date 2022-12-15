<?php

namespace App\Repository;

use App\Entity\Factory\ProductsImportFactory;
use App\Entity\ProductsImport;
use App\Utils\FileUploader\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @return void
     */
    public function createProductsImportWithFileUpload(UploadedFile $uploadedFile): void
    {
        $this->getEntityManager()->beginTransaction();

        $uploadedFileName = $this->fileUploader->upload($uploadedFile);

        $productsImport = ProductsImportFactory::create($uploadedFileName);

        $this->getEntityManager()->persist($productsImport);
        $this->getEntityManager()->flush();

        $this->getEntityManager()->commit();
    }
}