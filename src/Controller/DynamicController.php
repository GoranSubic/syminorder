<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\TagServices;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class DynamicController extends AbstractController
{
    /**
     * @Route("/kategorija/{slug}", name="category_show_front", options = { "expose" = true },)
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
     * @Route("/indikacija/{slug}", name="tagservice_show_front", options = { "expose" = true },)
     */
    public function showTagService(TagServices $tagService, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();

        /* find TagServices products */
        $repoProducts = $em->getRepository('App\Entity\Product');
//        $products = $repoProducts->findBy(['tagServices' => $tagService, 'enabled' => true]);
        $products = $repoProducts->findByTagServices($tagService->getId());
        $productsJson = count($products) ? $serializer->serialize($products, 'json', ['groups' => 'product:list']) : '';

        return $this->render('Front/show_tagservice/tagservice_front.html.twig', [
            'tagService' => $tagService,
            'productsdata' => $productsJson
        ]);
    }

    /**
     * @Route("/proizvod/{slug}", name="product_show_front", options = { "expose" = true },)
     */
    public function showProduct(Product $product, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('Front/show_product/product_front.html.twig', [
            'product' => $product,
        ]);
    }

}