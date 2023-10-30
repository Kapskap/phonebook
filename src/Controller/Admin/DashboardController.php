<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Phone;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(ContactCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Książka telefoniczna');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Kontakty', 'fas fa-map-marker-alt', Contact::class);
        yield MenuItem::linkToCrud('Telefony', 'fas fa-comments', Phone::class);
        yield MenuItem::linktoRoute('Powrót na stronę główną', 'fas fa-home', 'home');
    }
}
