<?php

namespace App\DataFixtures;

use App\Factory\ParticipantFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ParticipantFactory::createMany(2);
        $manager->flush();
    }
}
