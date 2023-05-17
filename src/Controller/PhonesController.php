<?php

namespace App\Controller;

use App\Entity\Phones;
use App\Repository\PhonesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PhonesController extends AbstractController
{

    #[Route('/', name: 'home_page')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    //przeglądanie wielu rekordów
    #[Route('/browse/{id}', name: 'browse_phones')]
    public function browse(EntityManagerInterface $entityManager, int $id=null): Response
    {
        $phonesRepository = $entityManager->getRepository(Phones::class);
        //bez sortowania
        $phones = $phonesRepository->findAll();
        //z sortowaniem
        //$phones = $phonesRepository->findBy([], ['Company' => 'ASC']);

        return $this->render('phones/browse.html.twig', ['phones' => $phones]);
    }

    //wyświetlanie 1 rekordu
    #[Route('/show/{id}', name: 'show_phones')]
    public function show($id, PhonesRepository $phonesRepository): Response
    {
        $phone=$phonesRepository->find($id);
        if (!$phone) {
            throw $this->createNotFoundException('Nie znaleziono id '.$id);
            }
        return $this->render('phones/show.html.twig', ['phone'=>$phone]);
    }


}


?>