<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Service\ContactService;

class ImportExportController extends AbstractController
{
    #[Route('/import', name: 'app_import')]
    function import(Request $request, ContactService $contactService)
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
                $created_at = new \DateTimeImmutable();
                while (($data = fgetcsv($handle)) !== false) {
                    $working = $contactService->insertContact($data[1], $data[2], $data[3], $created_at);
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

    #[Route('/export_excel', name: 'app_export_to_excel')]
    public function exportexcel(EntityManagerInterface $entityManager)
    {

        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        //Wstawianie danych:
        // Set cell name and merge cells
        $sheet->setCellValue('B1', 'Dane kontaktowe')->mergeCells('B1:D1');

        // Ustawienie nazw kolumn
        $columnNames = [
            'ID kontaktu',
            'Imię',
            'Nazwisko',
            'Firma',
        ];
        $columnLetter = 'A';
        foreach ($columnNames as $columnName) {
            $columnLetter++;
            $sheet->setCellValue($columnLetter.'2', $columnName);
        }

        // Dodanie danych pobranych z bazy do kolumn
        $contactRepository = $entityManager->getRepository(contact::class);
        $contacts = $contactRepository->findAllWithSort();

        $i=3;
        foreach ($contacts as $contact) {
            $columnValue = array($contact->getId(), $contact->getFirstName(), $contact->getLastName(), $contact->getCompany());
            $columnLetter = 'A';
            foreach ($columnValue as $value) {
                $columnLetter++;
                $sheet->setCellValue($columnLetter . $i, $value);
            }
            $i++;
        }

        $sheet->setTitle("Kontakty");

        // utworzenie dokumentu Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Utworzenie pliku tymczasowego w systemie
        $fileName = 'kontakty.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Utworzenie pliku excela w katalogu temp systemu
        $writer->save($temp_file);

        // Zwrócenie pliku excela jak załącznika
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

}
