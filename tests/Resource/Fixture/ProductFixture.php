<?php

namespace Tests\Resource\Fixture;

use App\Entity\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Tests\Utils\FakerUtilsTrait;

class ProductFixture extends Fixture
{
    use FakerUtilsTrait;

    const REFERENCE = 'product';

    public function load(ObjectManager $manager)
    {
        $faker = $this->getFaker();

        $product = ProductFactory::create(
            $faker->word(),
            $faker->sentence(),
            $faker->randomNumber(5),
            $faker->word(),
        );

        $manager->persist($product);
        $manager->flush();

        $this->addReference(self::REFERENCE, $product);
    }
}