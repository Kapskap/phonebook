<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExportController extends AbstractController
{
    #[Route('/export/plik.csv', name: 'app_export')]
    public function export(EntityManagerInterface $entityManager)
    {
        $contactRepository = $entityManager->getRepository(contact::class);
        //$contacts = $contactRepository->findBy([], ['company' => 'ASC']);
        $contacts = $contactRepository->findAllWithSort();

        $rows = array();
        foreach ($contacts as $contact) {
            $data = array($contact->getId(), $contact->getFirstName(), $contact->getLastName(), $contact->getCompany());

            $rows[] = implode(',', $data);
        }

        $content = implode("\n", $rows);
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }

}
