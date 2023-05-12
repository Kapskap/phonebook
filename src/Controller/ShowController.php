<?php

namespace App\Controller;

use App\Entity\Phones;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ShowController extends AbstractController
{
    #[Route('/show/{id}', name: 'show_phones')]
    public function show(EntityManagerInterface $entityManager, int $id=null): Response
    {
        //$dane = $entityManager->getRepository(Phones::class)->find($id);
        $daneRepository = $entityManager->getRepository(Phones::class);
        $dane = $daneRepository->findAll();


       // dd($dane);


        if (!$daneRepository) {
            throw $this->createNotFoundException(
                'Nie znaleziono w bazie osoby o id = '.$id
            );
        }

        return $this->render('phones/show.html.twig', ['dane' => $dane]);

        // $show='Dane telefoniczne ososby o id = '.$id;
        // $show.=' <br> Telefon: '.$dane->getNumber().' Nazwa firmy: '.$dane->getCompany();
        // return new Response($show);

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }
}

// namespace App\Controller;

// use App\Entity\Phones;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use App\Repository\PhonesRepository;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;
// // ...

// class ShowController extends AbstractController
// {
//     #[Route('/show/{id}', name: 'show_phones')]
//     public function show(int $id, PhonesRepository $phonesRepository): Response
//     {
//         $phones = $phonesRepository
//             ->find($id);
//            // dd($phones);
//             return new Response('Check out this great product: '.$phones.id);
        
//     }
// }

?>