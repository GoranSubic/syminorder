<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\CityDelivery;
use App\Entity\Product;
use App\Entity\ProductPicture;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(CategoryCrudController::class)->generateUrl());

        // you can also redirect to different pages depending on the current user
        if ('waiter1' === $this->getUser()->getUsername()) {
            return $this->redirect('product_index');
        }

        // you can also render some template to display a proper Dashboard
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        return $this->render('Admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Syminorder')

            // you can include HTML contents too (e.g. to link to an image)
//            ->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>')

            // the path defined in this method is passed to the Twig asset() function
//            ->setFaviconPath('favicon.svg')
            ->setFaviconPath('favicon.ico')

            // the domain used by default is 'messages'
//            ->setTranslationDomain('admin')
            ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoRoute('Ponuda Front', 'fa fa-shopping-bag', 'homepage'),
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Category'),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),

            MenuItem::section('Product'),
            MenuItem::linkToCrud('Products', 'fa fa-tags', Product::class),

            MenuItem::section('Images'),
            MenuItem::linkToCrud('Products images', 'fa fa-file', ProductPicture::class),

            MenuItem::section('Users'),
            MenuItem::linkToCrud('Users List', 'fa fa-file', User::class),

            MenuItem::section('City Delivery'),
            MenuItem::linkToCrud('Lista naselja', 'fa fa-file', CityDelivery::class),

            MenuItem::section("Logout"),
            MenuItem::linkToLogout('Logout', 'fa fa-exit'),
            ];
    }
}
