<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\ContactFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ContactFactory::new()->createMany(10000);
        $manager->flush();
    }
}
