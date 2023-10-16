<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    //przeglądanie wielu rekordów
    #[Route('/browse/{id}', name: 'browse_contact')]
    public function browse(EntityManagerInterface $entityManager, int $id=null): Response
    {
        $contactRepository = $entityManager->getRepository(Contact::class);
        //bez sortowania
        $contact = $contactRepository->findAll();
        //z sortowaniem
        //$contact = $contactRepository->findBy([], ['Company' => 'ASC']);

        return $this->render('phone/browse.html.twig', ['contacts' => $contact]);
    }

    //wyświetlanie 1 rekordu
    #[Route('/show/{id}', name: 'show_contact')]
    public function show($id, ContactRepository $contactRepository): Response
//    public function show($id, ContactRepository $contactRepository, PhonesRepository $phonesRepository): Response
    {
        $contact=$contactRepository->find($id);
        if (!$contact) {
            throw $this->createNotFoundException('Nie znaleziono id '.$id);
        }

        $createdAt=$contact->getCreatedAt()->format('Y-m-d');
        return $this->render('phone/show.html.twig', ['contact'=>$contact, 'createdat'=>$createdAt]);
    }

}


