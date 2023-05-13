<?php

namespace App\Controller;

use App\Entity\Phones;
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

    #[Route('/show/{id}', name: 'show_phones')]
    public function show(EntityManagerInterface $entityManager, int $id=null): Response
    {
        //$dane = $entityManager->getRepository(Phones::class)->find($id);
        $daneRepository = $entityManager->getRepository(Phones::class);
        $dane = $daneRepository->findAll();

        return $this->render('phones/show.html.twig', ['dane' => $dane]);
    }


}


?>