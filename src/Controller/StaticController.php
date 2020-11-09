<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController
{
    /**
    * @Route("/about")
    */
    public function about()
    {
        $words = ['minute', 'order', 'drink', 'food', 'coffee', '...'];
        return $this->render('Front/static_pages/about_body.html.twig', [
            'words' => $words
        ]);
    }
}