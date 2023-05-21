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

    #[Route('/edit/{id}', name: 'edit_phones')]
    public function updatePhones(EntityManagerInterface $entityManager, int $id): Response
    {
        $phone = $entityManager->getRepository(Phones::class)->find($id);

        if (!$phone) {
            throw $this->createNotFoundException(
                'Nie znaleziono id '.$id
            );
        }
        $phone->setCompany('Nowa Firma');
        $phone->setNumber('+48 000 000 000');
        $entityManager->flush();

        return $this->redirectToRoute('show_phones', [
            'id' => $phone->getId()
        ]);
    }

    #[Route('/del/{id}', name: 'del_phones')]
    public function deletePhones(EntityManagerInterface $entityManager, int $id): Response
    {
        $phone = $entityManager->getRepository(Phones::class)->find($id);

        if (!$phone) {
            throw $this->createNotFoundException(
                'Nie znaleziono id '.$id
            );
        }
        $entityManager->remove($phone);
        $entityManager->flush();

        return $this->redirectToRoute('browse_phones');
    }
}
?>