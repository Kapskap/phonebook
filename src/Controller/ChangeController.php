<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddFormType;

class ChangeController extends AbstractController
{
    //dodawanie 1 rekordu do bazy przy użyciu formularza
    #[Route('/add', name: 'add_contact')]
    public function createContact(EntityManagerInterface $entityManager, Request $request): Response
    {
        //tworzenie formularza
        $form = $this->createForm(AddFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //pobieranie danych z formularza
            $contact = $form->getData();
            $entityManager->persist($contact);

            //dodawanie telefonu do kontaktu
            $phone1 = new Phone();
            $phone1->setNumber('+48 111 222 333');
            $phone1->setTypePhone('komórkowy');
            $phone1->setContact($contact);
            $entityManager->persist($phone1);

            $phone2 = new Phone();
            $phone2->setNumber('+48 111 111 111');
            $phone2->setTypePhone('praca');
            $phone2->setContact($contact);
            $entityManager->persist($phone2);

            $entityManager->flush();

            //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
            $this->addFlash('success', 'Dodano wpis o id: '.$contact->getID());

            // return new Response('Dodano nowy kontakt o  id = '.$phone->getId());
            return $this->redirectToRoute('browse_contact');
        }

        return $this->render('forms/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit_contact')]
    public function updateContact(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        $contact = $entityManager->getRepository(Contact::class)->find($id);

        //tworzenie formularza
        $form = $this->createForm(AddFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
            $this->addFlash('success', 'Zmioniono wpis o id: '.$id);

            return $this->redirectToRoute('browse_contact');
        }

        return $this->render('forms/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/del/{id}', name: 'del_contact')]
    public function deleteContact(EntityManagerInterface $entityManager, int $id): Response
    {
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw $this->createNotFoundException(
                'Nie znaleziono id '.$id
            );
        }
        $entityManager->remove($contact);
        $entityManager->flush();

        //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
        $this->addFlash('success', 'Usunięto wpis o id: '.$id);

        return $this->redirectToRoute('browse_contact');
    }
}
