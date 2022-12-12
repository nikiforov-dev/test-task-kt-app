<?php

namespace App\Entity\Factory;

use App\Entity\Product;

class ProductFactory
{
    public static function create(string $name, string $description, int $weight, string $category): Product
    {
        return (new Product())
            ->setId(null)
            ->setName($name)
            ->setDescription($description)
            ->setWeight($weight)
            ->setCategory($category)
        ;
    }
}