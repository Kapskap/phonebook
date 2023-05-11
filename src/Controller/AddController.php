<?php

namespace App\Controller;

use App\Entity\Phones;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends AbstractController
{
    //dodawanie 1 przykładowego rekordu do bazy Phones
    #[Route('/add', name: 'add_contact')]
    public function createPhones(EntityManagerInterface $entityManager): Response
    {
        $phone = new Phones();
        $phone->setNumber('+48 123 456 789');
        $phone->setCompany('Biuro Podrozy Slask');
        $data=date("Y-m-d");
        $phone->setData(\DateTime::createFromFormat('Y-m-d', $data));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($phone);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Dodano nowy produkt o  id = '.$phone->getId());
    }
}
?>