<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SearchFormType;
use App\Entity\Contact;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search_contact')]
    public function search(EntityManagerInterface $entityManager, Request $request)
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $query = $form->get('query')->getData();

            // Wykonaj zapytanie do bazy danych na podstawie $query
 //           $contact = $entityManager->getRepository(Contact::class)->findBy(['id' => $query]);

            // Wykonywanie zapytania po Company niestety nie zwraca obiektu array
//            $phone = $entityManager->getRepository(Phones::class)->findOneBy(['company' => $query]);

            //Wykorzytanie Doctrine Query Language
 //           $contact = $entityManager->getRepository(Contact::class)->findAllThanText($query);

            //Wykorzystanie języka SQL
            $contact = $entityManager->getRepository(Contact::class)->findAllSQL($query);

            // Przekaż wyniki do widoku
            return $this->render('search/result.html.twig', ['contacts' => $contact, 'query'=>$query]);
        }

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
