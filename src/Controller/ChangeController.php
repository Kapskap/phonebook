<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AddContactFormType;
use App\Form\AddPhoneFormType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ChangeController extends AbstractController
{
    //dodawanie 1 rekordu do bazy przy użyciu formularza
    #[Route('/admin/add', name: 'add_contact')]
    public function createContact(EntityManagerInterface $entityManager, Request $request,
                                  #[Autowire('%photo_dir%')] string $photoDir): Response
    {
        //tworzenie formularza
        $form = $this->createForm(AddContactFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //pobieranie danych z formularza
            $contact = $form->getData();

            if ($photo = $form['photo']->getData()) {
                $filename = 'IMG_'.bin2hex(random_bytes(5)).'.'.$photo->guessExtension();
                $photo->move($photoDir, $filename);
                $contact->setPhotoFilename($filename);
            }

            $entityManager->persist($contact);
            $entityManager->flush();

            //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
            $this->addFlash('success', 'Dodano kontakt o id: '.$contact->getID());

            return $this->redirectToRoute('show_contact',['id'=>$contact->getID()]);
        }

        return $this->render('forms/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/edit/{id}', name: 'edit_contact')]
    public function updateContact(EntityManagerInterface $entityManager, int $id, Request $request,
                                  #[Autowire('%photo_dir%')] string $photoDir): Response
    {
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        //tworzenie formularza
        $form = $this->createForm(AddContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($photo = $form['photo']->getData()) {
                $filename=$contact->getPhotoFilename();
                if ($filename==null) {$filename = 'IMG_'.bin2hex(random_bytes(5)).'.'.$photo->guessExtension();}
                $photo->move($photoDir, $filename);
                $contact->setPhotoFilename($filename);
            }

            $entityManager->persist($contact);
            $entityManager->flush();

            //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
            $this->addFlash('success', 'Zmioniono dane kontaktowe dla id: '.$id);

            return $this->redirectToRoute('show_contact',['id'=>$id]);
        }

        return $this->render('forms/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/del/{id}', name: 'del_contact')]
    public function deleteContact(EntityManagerInterface $entityManager, int $id): Response
    {
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw $this->createNotFoundException(
                'Nie znaleziono id '.$id
            );
        }

        $file = $contact->getPhotoFilename();
        if (NULL != $file) {
            $path = sprintf(
                '%s/%s',
                $this->getParameter('photo_dir'),
                $file
            );

            @unlink($path);
        }

        $entityManager->remove($contact);
        $entityManager->flush();

        //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
        $this->addFlash('success', 'Usunięto kontakt o id: '.$id);

        return $this->redirectToRoute('browse_contact');
    }

    #[Route('/admin/add_phone/{id}', name: 'add_phone')]
    public function createPhone(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $contact = $entityManager->getRepository(Contact::class)->find($id);

        if (!$contact) {
            throw $this->createNotFoundException(
                'Nie mogę dodać telefonu do kontaktu o id '.$id.' - kontakt nie istnieje.'
            );
        }

        //tworzenie formularza
        $form = $this->createForm(AddPhoneFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //pobieranie danych z formularza
            $phone = $form->getData();
            $phone->setContact($contact);
            $entityManager->persist($phone);

            $entityManager->flush();

            //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
            $this->addFlash('success', 'Dodano numer telefonu o id: '.$phone->getID());

            // return new Response('Dodano nowy kontakt o  id = '.$phone->getId());
            return $this->redirectToRoute('show_contact',['id'=>$id]);
        }

        return $this->render('forms/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/edit_phone/{id}/{cid}', name: 'edit_phone')]
    public function updatePhone(EntityManagerInterface $entityManager, int $id, $cid, Request $request): Response
    {
        $contact = $entityManager->getRepository(Contact::class)->find($cid);
        if (!$contact) {
            throw $this->createNotFoundException(
                'Nie mogę edytować telefonu dla kontaktu o id '.$cid.' - kontakt nie istnieje.'
            );
        }

        $phone = $entityManager->getRepository(Phone::class)->find($id);
        if (!$phone) {
            throw $this->createNotFoundException(
                'Nie mogę edytować telefonu dla kontaktu o id '.$id.' - telefon nie istnieje.'
            );
        }
        //tworzenie formularza
        $form = $this->createForm(AddPhoneFormType::class, $phone);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //pobieranie danych z formularza
            $phone = $form->getData();
            $phone->setContact($contact);
            $entityManager->persist($phone);
            $entityManager->flush();

            //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
            $this->addFlash('success', 'Zmioniono wpis o id: '.$id);

            return $this->redirectToRoute('show_contact',['id'=>$cid]);
        }

        return $this->render('forms/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/del_phone/{id}/{cid}', name: 'del_phone')]
    public function deletePhone(EntityManagerInterface $entityManager, int $id, $cid): Response
    {
        $phone = $entityManager->getRepository(Phone::class)->find($id);
        if (!$phone) {
            throw $this->createNotFoundException(
                'Nie znaleziono telefonu o numerze id '.$id
            );
        }
        $entityManager->remove($phone);
        $entityManager->flush();

        //genrowanie powiadomień które są wyświetleane z poziomu głównego szablonu
        $this->addFlash('success', 'Usunięto numer telefonu o id: '.$id);

        return $this->redirectToRoute('show_contact',['id'=>$cid]);
    }
}
