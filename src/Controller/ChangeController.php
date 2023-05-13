<?php

namespace App\Controller;

use App\Entity\Phones;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChangeController extends AbstractController
{
    //dodawanie 1 przykładowego rekordu do bazy Phones
    #[Route('/add', name: 'add_contact')]
    public function createPhones(EntityManagerInterface $entityManager): Response
    {
        $phone = new Phones();
        $phone->setNumber('+48 111 222 333');
        $phone->setCompany('Biuro Podrozy XX');
        //$data=date("Y-m-d");
        //$phone->setData(\DateTime::createFromFormat('Y-m-d', $data));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($phone);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Dodano nowy produkt o  id = '.$phone->getId());
    }

    #[Route('/edit/{id}', name: 'edit_contact')]
    public function update(EntityManagerInterface $entityManager, int $id): Response
    {
        $dane = $entityManager->getRepository(Phones::class)->find($id);

        if (!$dane) {
            throw $this->createNotFoundException(
                'Nie znaleziono id '.$id
            );
        }

        $dane->setCompany('Nowa Firma');
        $entityManager->flush();

        return $this->redirectToRoute('show_phones', [
            'id' => $dane->getId()
        ]);
    }
}
?>