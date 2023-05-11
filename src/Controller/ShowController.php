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
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $dane = $entityManager->getRepository(Phones::class)->find($id);

        if (!$dane) {
            throw $this->createNotFoundException(
                'Nie znaleziono w bazie osoby o id = '.$id
            );
        }
        $show='Dane telefoniczne ososby o id = '.$id;
        $show.=' <br> Telefon: '.$dane->getNumber().' Nazwa firmy: '.$dane->getCompany();
        return new Response($show);

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }
}
?>