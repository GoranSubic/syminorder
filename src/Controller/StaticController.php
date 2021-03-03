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
    * @Route("/privacy", name="app_static_privacy")
    */
    public function privacy()
    {
        return $this->render('Front/static_pages/privacy-policy.html.twig');
    }

    /**
    * @Route("/terms", name="app_static_terms")
    */
    public function terms()
    {
        return $this->render('Front/static_pages/terms-conditions.html.twig');
    }

}