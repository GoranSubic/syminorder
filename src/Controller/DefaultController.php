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
     * @Route("/porudzbine", name="app_orders")
     */
    public function index(SerializerInterface $serializer)
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