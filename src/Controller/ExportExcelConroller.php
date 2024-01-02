<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Include PhpSpreadsheet required namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ExportExcelConroller extends AbstractController
{
    #[Route('/export_excel', name: 'app_export_to_excel')]
    public function exportexcel(EntityManagerInterface $entityManager)
    {

        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        //Wstawianie danych:
        // Set cell name and merge cells
        $sheet->setCellValue('A1', 'Dane kontaktowe')->mergeCells('A1:D1');

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
