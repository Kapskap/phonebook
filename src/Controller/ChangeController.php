<?php

namespace App\Controller;

use App\Entity\Phones;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddFormType;

class ChangeController extends AbstractController
{
    //dodawanie 1 rekordu do bazy Phones przy użyciu formularza
    #[Route('/add', name: 'add_phones')]
    public function createPhones(EntityManagerInterface $entityManager, Request $request): Response
    {
        //tworzenie formularza
        $form = $this->createForm(AddFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //pobieranie danych z formularza
            $formData = $form->getData();
            $number=$formData['number'];
            $company=$formData['company'];
            $firstname=$formData['firstname'];
            $lastname=$formData['lastname'];

            //dodawanie rekordów do bazy
            $phone = new Phones();
            $phone->setNumber($number);
            $phone->setCompany($company);
            if ($firstname!=null) $phone->setFirstname($firstname);
            if ($lastname!=null) $phone->setLastname($lastname);
            $entityManager->persist($phone);
            $entityManager->flush();

            //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
            $this->addFlash('success', 'Dodano wpis o id: '.$phone->getID());

           // return new Response('Dodano nowy kontakt o  id = '.$phone->getId());
           return $this->redirectToRoute('browse_phones');
        }

        return $this->render('forms/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/edit/{id}', name: 'edit_phones')]
    public function updatePhones(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
//        $phone = $entityManager->getRepository(Phones::class)->find($id);
//dd($phone);
        //tworzenie formularza
//        $form = $this->createForm(AddFormType::class, $phone);
//        $form->handleRequest($request);

//        if ($form->isSubmitted() && $form->isValid()) {
//            //pobieranie danych z formularza
//            $formData = $form->getData();
//            $number=$formData['number'];
//            $company=$formData['company'];
//            $firstname=$formData['firstname'];
//            $lastname=$formData['lastname'];
//
//            //dodawanie rekordów do bazy
//            $phone = new Phones();
//            $phone->setNumber($number);
//            $phone->setCompany($company);
//            if ($firstname!=null) $phone->setFirstname($firstname);
//            if ($lastname!=null) $phone->setLastname($lastname);
//            $entityManager->persist($phone);
//            $entityManager->flush();
//
//            //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
//            $this->addFlash('success', 'Dodano wpis o id: '.$phone->getID());
//
//            // return new Response('Dodano nowy kontakt o  id = '.$phone->getId());
//            return $this->redirectToRoute('browse_phones');
//        }

//        return $this->render('forms/index.html.twig', [
//            'form' => $form->createView(),
//        ]);

        //Wartości pisane "na siłę" zamiast przy uzyciu formularza
        $phone = $entityManager->getRepository(Phones::class)->find($id);

        if (!$phone) {
            throw $this->createNotFoundException(
                'Nie znaleziono id '.$id
            );
        }
        $phone->setCompany('Nowa Firma');
        $phone->setNumber('+48 000 000 000');
        $entityManager->flush();

        //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
        $this->addFlash('success', 'Zmioniono wpis o id: '.$id);

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

        //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
        $this->addFlash('success', 'Usunięto wpis o id: '.$id);

        return $this->redirectToRoute('browse_phones');
    }
}
?>