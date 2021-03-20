<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/changestatus/{id}", name="order_change_status", methods={"GET","PATCH"})
     */
    public function changeStatus(Order $order): RedirectResponse
    {
        if ($order && $order->getStatus() == 'cart') {
            $order->setStatus('processing');
            $order->setProcessAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_indications', [
            'id' => $order->getId(),
        ]);
    }

}
