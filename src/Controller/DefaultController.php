<?php


namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CategoryRepository $nestedTreeRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConfiguration()->addCustomHydrationMode('tree', 'Gedmo\Tree\Hydrator\ORM\TreeObjectHydrator');
        $repo = $em->getRepository('App\Entity\Category');

        $tree = $repo->createQueryBuilder('node')->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true)
            ->getResult('tree');

        return $this->render('Front/products_list/index.html.twig', [
            'categories' => $tree[0]->getChildren(),
        ]);
    }
}