<?php


namespace App\Controller;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use function Webmozart\Assert\Tests\StaticAnalysis\true;

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


        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('enabled', true));

        $tree = $repo->createQueryBuilder('node')
            ->addCriteria($criteria)
            ->getQuery()
            ->setHint(\Doctrine\ORM\Query::HINT_INCLUDE_META_COLUMNS, true)
            ->getResult('tree');

        $categories = $tree ? $tree[0]->getChildren() : [];
        return $this->render('Front/products_list/index.html.twig', [
            'categories' => $categories
        ]);
    }
}