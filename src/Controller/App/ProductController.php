<?php

namespace App\Controller\App;

use App\Controller\App\DTO\ProductsQueryDTO;
use App\Controller\App\Form\ProductsQueryForm;
use App\Entity\Product;
use App\Filters\FilterManager;
use App\Repository\ProductRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    private const PRODUCTS_PAGE_SIZE     = 10;
    private const PRODUCTS_PAGE_TEMPLATE = 'products.html.twig';

    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var FilterManager
     */
    private FilterManager $filterManager;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     * @param PaginatorInterface $paginator
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        PaginatorInterface $paginator,
        ProductRepository $productRepository
    )
    {
        $this->formFactory       = $formFactory;
        $this->paginator         = $paginator;
        $this->productRepository = $productRepository;

        $this->filterManager = new FilterManager();
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function productsAction(Request $request): Response
    {
        $query = $this->handleProductsQuery($request);

        $filterData   = $query->getFilterData();
        $orderByData  = $query->getOrderByData();

        $qb = $this->productRepository->getAllQB();

        $this->filterManager->applyFilters($qb, $filterData, Product::class);
        $this->filterManager->applyOrderBy($qb, $orderByData);

        $productsPagination = $this->paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            self::PRODUCTS_PAGE_SIZE,
        );

        return $this->render(self::PRODUCTS_PAGE_TEMPLATE, $this->prepareTwigValues($query, $productsPagination));
    }

    /**
     * @param ProductsQueryDTO $query
     * @param PaginationInterface $productsPagination
     *
     * @return array
     */
    private function prepareTwigValues(ProductsQueryDTO $query, PaginationInterface $productsPagination): array
    {
        return [
            'products' => $productsPagination,
            'query'    => $query
        ];
    }

    /**
     * @param Request $request
     *
     * @return ProductsQueryDTO
     */
    private function handleProductsQuery(Request $request): ProductsQueryDTO
    {
        $queryMap = $request->query->all();
        $form     = $this->formFactory->create(ProductsQueryForm::class);

        $form->submit($queryMap);

        return $form->getData();
    }
}