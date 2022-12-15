<?php

namespace App\Controller\App;

use App\Repository\ProductsImportRepository;
use App\Utils\FileUploader\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ImportProductsController extends AbstractController
{
    /**
     * @var ProductsImportRepository
     */
    private ProductsImportRepository $productsImportRepository;

    /**
     * @param ProductsImportRepository $productsImportRepository
     */
    public function __construct(ProductsImportRepository $productsImportRepository)
    {
        $this->productsImportRepository = $productsImportRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function importProductsAction(Request $request): Response
    {
        return $this->render('import.html.twig');
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
            return new Response('Import file is missed!', 400);
        }

        try {
            $this->productsImportRepository->createProductsImportWithFileUpload($file);
        } catch (Throwable $e) {
            return new JsonResponse("Can`t upload file! Reason: {$e->getMessage()}", 400);
        }

        return new JsonResponse("Uploaded", 200);
    }
}