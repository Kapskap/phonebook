<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhonesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PhoneController extends AbstractController
{
//
//    #[Route('/', name: 'home')]
//    public function home(): Response
//    {
//        return $this->render('base.html.twig');
//    }
//
//    //przeglądanie wielu rekordów
//    #[Route('/browse/{id}', name: 'browse_phone')]
//    public function browse(EntityManagerInterface $entityManager, int $id=null): Response
//    {
//        $phonesRepository = $entityManager->getRepository(Phone::class);
//        //bez sortowania
//        $phones = $phonesRepository->findAll();
//        //z sortowaniem
//        //$phone = $phonesRepository->findBy([], ['Company' => 'ASC']);
//
//        return $this->render('phone/browse.html.twig', ['phones' => $phones]);
//    }
//
//    //wyświetlanie 1 rekordu
//    #[Route('/show/{id}', name: 'show_phone')]
//    public function show($id, PhonesRepository $phonesRepository): Response
//    {
//        $phone=$phonesRepository->find($id);
//        if (!$phone) {
//            throw $this->createNotFoundException('Nie znaleziono id '.$id);
//            }
//       $createdAt=$phone->getCreatedAt()->format('Y-m-d');
//        return $this->render('phone/show.html.twig', ['phones'=>$phone, 'createdat'=>$createdAt]);
//    }

}


