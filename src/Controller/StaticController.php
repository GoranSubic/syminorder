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
    * @Route("/about", name="app_static_about")
    */
    public function about()
    {
        return $this->render('Front/static_pages/about.html.twig');
    }

    /**
     * @Route("/orders/{id?}", name="app_static_orders")
     * @ParamConverter("order", class="App\Entity\Order")
     */
    public function orders(Order $order = null)
    {
        return $this->render('Front/static_pages/orders.html.twig', [
            "orderchanged" => $order
        ]);
    }
}