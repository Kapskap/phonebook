<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $contact = new Contact();
        $contact->setFirstName('Adam')
            ->setLastName('Nowak')
            ->setCompany('Firma Test');
        if (rand(1, 10) > 2) {
            $contact->setCreatedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 100))));
        }



        // $product = new Product();
        $manager->persist($contact);

        $manager->flush();
    }
}
