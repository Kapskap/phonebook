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
    //dodawanie 1 przykładowego rekordu do bazy Phones
    #[Route('/add', name: 'add_phones')]
    public function createPhones(EntityManagerInterface $entityManager, Request $request): Response
    {
        //tworzenie formularza
        $form = $this->createForm(AddFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //pobieranie danych z formularza
            $formData = $form->getData();
            $number=$formData['phonenumber'];
            $company=$formData['company'];

            //dodawanie rekordów do bazy
            $phone = new Phones();
            $phone->setNumber($number);
            $phone->setCompany($company);
            $entityManager->persist($phone);
            $entityManager->flush();

            return new Response('Dodano nowy kontakt o  id = '.$phone->getId());
        }

        return $this->render('example/index.html.twig', [
            'form' => $form->createView(),
        ]);

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