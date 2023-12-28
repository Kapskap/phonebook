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
    #[Route('/ee', name: 'app_exportexcel')]
    public function exportexcel()
    {

        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Hello World !');
        $sheet->setTitle("My First Worksheet");

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'my_first_excel_symfony4.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);


//        $contactRepository = $entityManager->getRepository(contact::class);
//        //$contacts = $contactRepository->findBy([], ['company' => 'ASC']);
//        $contacts = $contactRepository->findAllWithSort();
//
//        $rows = array();
//        foreach ($contacts as $contact) {
//            $data = array($contact->getId(), $contact->getFirstName(), $contact->getLastName(), $contact->getCompany());
//
//            $rows[] = implode(',', $data);
//        }
//
//        $content = implode("\n", $rows);
//        $response = new Response($content);
//        $response->headers->set('Content-Type', 'text/csv');
//
//        return $response;

    }

}
