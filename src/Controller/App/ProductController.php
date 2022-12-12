<?php

namespace App\Controller\App;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @param PaginatorInterface $paginator
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(PaginatorInterface $paginator, ProductRepository $productRepository)
    {
        $this->paginator         = $paginator;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function productsAction(Request $request): Response
    {
        $productsPagination = $this->paginator->paginate(
            $this->productRepository->getAllProductsQuery(),
            $request->query->getInt('page', 1),
            10,
        );

        return $this->render('products.html.twig', [
            'products' => $productsPagination
        ]);
    }
}