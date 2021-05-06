<?php

namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 20; $i++) {

            // $product = new Product();
            $item = new Item();

            $item->setTitle('title '. $i);
            $item->setDescription('This is the description for the article '. $i);
            $manager->persist($item);
            // $manager->persist($product);
        }

        $manager->flush();
    }
}
