<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Twig\Environment;



class ContactController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    //przeglądanie wielu rekordów
//    #[Route('/browse/{id}', name: 'browse_contact')]
//    public function browse(EntityManagerInterface $entityManager, int $id=null): Response
//    {
//        $contactRepository = $entityManager->getRepository(Contact::class);
//        //bez sortowania
//        $contact = $contactRepository->findAll();
//        //z sortowaniem
//        //$contact = $contactRepository->findBy([], ['Company' => 'ASC']);
//
//        return $this->render('phone/browse.html.twig', ['contacts' => $contact]);
//    }

    #[Route('/browse/{id}', name: 'browse_contact')]
    public function browse(Request $request, Environment $twig, ContactRepository $contactRepository, int $id=null): Response
    {
        $valueData = $request->query->getInt('how', 0);
        if ($valueData == 0) {
            $valueData = 25;
        }

        $form = $this->createFormBuilder()
            ->add('howMany', ChoiceType::class, [
                'label' => ' ',
                'choices' => [
                    '10' => 10,
                    '25' => 25,
                    '50' => 50,
                ],
                'data' => $valueData,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zmień ilość'
            ])
            ->getForm();

        $form->handleRequest($request);
        $paginator_per_page = $form->get('howMany')->getData();

//        $paginator_per_page = 25;

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $contactRepository->getCommentPaginator($offset, $paginator_per_page);

        return $this->render('phone/browse.html.twig',[
                'form' => $form->createView(),
                'contacts' => $paginator,
                'previous' => $offset - $paginator_per_page,
                'next' => min(count($paginator), $offset + $paginator_per_page),
//                'previous' => $offset - ContactRepository::PAGINATOR_PER_PAGE,
//                'next' => min(count($paginator), $offset + ContactRepository::PAGINATOR_PER_PAGE),
                'paginator_per_page' => $paginator_per_page,
        ]);
    }

    //wyświetlanie 1 rekordu
    #[Route('/show/{id}', name: 'show_contact')]
    public function show($id, ContactRepository $contactRepository): Response
    {
        $contact=$contactRepository->find($id);
        if (!$contact) {
            throw $this->createNotFoundException('Nie znaleziono id '.$id);
        }

        $createdAt=$contact->getCreatedAt()->format('Y-m-d');
        return $this->render('phone/show.html.twig', ['contact'=>$contact, 'createdat'=>$createdAt]);
    }

}


