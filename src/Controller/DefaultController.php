<?php


namespace App\Controller;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(SerializerInterface $serializer)
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
            ->getResult('tree')
        ;

        $categories = $tree ? $tree[0]->getChildren() : [];
        $jsonCat = $serializer->serialize($categories, 'json', ['groups' => 'category:list']);
        return $this->render('Front/categories_list/index.html.twig', [
//            'categories' => $categories,
            'categoriesJSON' => $jsonCat
        ]);
    }

}