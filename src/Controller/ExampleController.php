<?php

// src/Controller/ExampleController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AddFormType;

class ExampleController extends AbstractController
{
    /**
     * @Route("/example", name="example")
     */
    public function example(Request $request): Response
    {
        $form = $this->createForm(AddFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Przetwarzanie danych formularza

            // Możesz uzyskać dostęp do danych formularza za pomocą $form->getData()
            // Przykładowo: $formData = $form->getData();

            // Wykonaj dowolne działania na danych formularza, np. zapisz je w bazie danych

            // Przekierowanie po pomyślnym przetworzeniu formularza
            return $this->redirectToRoute('success');
        }

        return $this->render('example/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/success", name="success")
     */
    public function success(): Response
    {
        return new Response('Formularz został przesłany poprawnie!');
    }
}

?>