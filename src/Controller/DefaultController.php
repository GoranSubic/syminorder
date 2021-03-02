<?php


namespace App\Controller;

use ApiPlatform\Core\Api\IriConverterInterface;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App\Entity\Category');

        $rootNode = $repo->findOneBy(['name' => 'Home']);
        /** @var ArrayCollection|Category[] $children */
        $children = $repo->getChildren($rootNode, true, 'name');

        $childrenEnabled = array_filter($children, function ($cat) {
            return $cat->getEnabled() === true;
        });
        $childrenEnabledFromZero = array_values($childrenEnabled);


        return $this->render('Front/static_pages/homepage.html.twig', [
            'categories' => $childrenEnabledFromZero
        ]);
    }

    /**
     * @Route("/category/{slug}", name="category_show_front")
     */
    public function showCategory(Category $category, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();

        /* find category direct childred */
        $repoCat = $em->getRepository('App\Entity\Category');
        /** @var ArrayCollection|Category[] $children */
        $categoryChildren = $repoCat->getChildren($category, true, 'name');
        $childrenEnabled = array_filter($categoryChildren, function ($cat) {
            return $cat->getEnabled() === true;
        });
        $childrenEnabledFromZero = array_values($childrenEnabled);

        /* find category products */
        $repoProducts = $em->getRepository('App\Entity\Product');
        $products = $repoProducts->findBy(['category' => $category, 'enabled' => true]);
        $productsJson = $serializer->serialize($products, 'json', ['groups' => 'product:list']);

        return $this->render('Front/category/category_front.html.twig', [
            'category' => $category,
            'categories' => $childrenEnabledFromZero,
            'productsdata' => count($products) ? $productsJson : ''
        ]);
    }

    /**
     * @Route("/porudzbine", name="app_orders")
     */
    public function orders(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('App\Entity\Category');

        $rootNode = $repo->findOneBy(['name' => 'Home']);
        /** @var ArrayCollection|Category[] $children */
        $children = $repo->getChildren($rootNode, true, 'name');

        $childrenEnabled = array_filter($children, function ($cat) {
           return $cat->getEnabled() === true;
        });
        $childrenEnabledFromZero = array_values($childrenEnabled);

        $jsonCat = $serializer->serialize($childrenEnabledFromZero, 'json', ['groups' => 'category:list']);
        return $this->render('Front/categories_list/index.html.twig', [
            'categoriesJSON' => $jsonCat
        ]);
    }

}