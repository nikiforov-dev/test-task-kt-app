<?php

namespace App\Filters\Product;

use App\Filters\AbstractFilter;
use App\Filters\FilterInterface;

class ProductDescriptionFilter extends AbstractFilter implements FilterInterface
{
    /**
     * {@inheritdoc}
     */
    protected string $entityVariableName = 'description';

    /**
     * {@inheritdoc}
     */
    protected string $variable = 'description';
}