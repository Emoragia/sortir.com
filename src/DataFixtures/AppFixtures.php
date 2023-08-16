<?php

namespace App\DataFixtures;

use App\Factory\ParticipantFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        ParticipantFactory::createMany(4);
        $manager->flush();
    }
}
