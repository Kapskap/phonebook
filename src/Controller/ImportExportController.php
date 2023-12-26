<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ImportExportController extends AbstractController
{
    #[Route('/import', name: 'app_import')]
    function import(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createFormBuilder()
            ->add('submitFile', FileType::class, [
                'label' => ' '
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zatwierdź'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile */
            $file = $form->get('submitFile')->getData();
            // Otwarcie pliku
            if (($handle = fopen($file->getPathname(), "r")) !== false) {
                // Przetwarzanie danych.
                while (($data = fgetcsv($handle)) !== false) {

                    $query = "INSERT INTO contact (first_name, last_name, company)
                    VALUES(:first_name, :last_name, :company)";

                    $stmt = $entityManager->getConnection()->prepare($query);

                    $r = $stmt->execute(array(
                        'first_name' => $data[1],
                        'last_name' => $data[2],
                        'company' => $data[3],
                        // 'created_at' => $row['created_at'],
                    ));
                }

                fclose($handle);
                $this->addFlash('success', 'Dane zaimportowano poprawnie ');
                return $this->redirectToRoute('browse_contact');
            }
        }
        return $this->render('forms/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }

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
