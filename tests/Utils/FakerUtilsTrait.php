<?php

namespace Tests\Utils;

use Faker\Factory;
use Faker\Generator;

trait FakerUtilsTrait
{
    /**
     * @return Generator
     */
    public function getFaker(): Generator
    {
        return Factory::create();
    }
}