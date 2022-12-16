<?php

namespace App\Controller\App;

use App\Repository\ProductsImportRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ImportProductsController extends AbstractController
{
    private const PRODUCTS_IMPORT_PAGE_SIZE = 10;

    /**
     * @var ProductsImportRepository
     */
    private ProductsImportRepository $productsImportRepository;

    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * @param PaginatorInterface $paginator
     * @param ProductsImportRepository $productsImportRepository
     */
    public function __construct(PaginatorInterface $paginator, ProductsImportRepository $productsImportRepository)
    {
        $this->paginator                = $paginator;
        $this->productsImportRepository = $productsImportRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function importProductsAction(Request $request): Response
    {
        $qb = $this->productsImportRepository->getAllQB();

        $pagination = $this->paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            self::PRODUCTS_IMPORT_PAGE_SIZE
        );

        return $this->render('import.html.twig', ['imports' => $pagination]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function uploadXmlFileAction(Request $request): Response
    {
        /** @var UploadedFile|null $file */
        $file = $request->files->get('import_file');

        if ($file === null) {
            return new JsonResponse(['error' => 'Import file is missed!'], 400);
        }

        $fileName = $this->productsImportRepository->createProductsImportWithFileUpload($file);

        return new JsonResponse([
            "status"    => "Uploaded",
            "file_name" => $fileName
        ], 200);
    }
}