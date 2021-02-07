<?php


namespace App\Controller;

use App\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{
    /**
    * @Route("/onama", name="app_static_about")
    */
    public function about()
    {
        return $this->render('Front/static_pages/about.html.twig');
    }

    /**
     * @Route("/", name="homepage")
     */
    public function orders()
    {
        return $this->render('Front/static_pages/homepage.html.twig');
    }
}