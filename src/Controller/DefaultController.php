<?php


namespace App\Controller;

use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $children = $repo->getChildren($rootNode, true, 'position', 'desc');

        $childrenEnabled = array_filter($children, function ($cat) {
            return $cat->getEnabled() === true && $cat->getShowOnFront() === true;
        });
        $childrenEnabledFromZero = array_values($childrenEnabled);


        return $this->render('Front/static_pages/homepage.html.twig', [
            'categories' => $childrenEnabledFromZero
        ]);
    }

    /**
     * @Route("/indikacije", name="app_indications")
     */
    public function indications(SerializerInterface $serializer)
    {
        $configDisplayOfferBy = $this->getParameter('syminorder.offer.configDisplayOfferBy');
        $jsonCat = NULL;
        $jsonServices = NULL;
        $em = $this->getDoctrine()->getManager();

        if ($configDisplayOfferBy == 'tagservices') {
            $repo = $em->getRepository('App\Entity\TagServices');
            $servicesEnabled = $repo->findBy(['enabled' => true], ['name' => 'asc']);
            $jsonServices = $serializer->serialize($servicesEnabled, 'json', ['groups' => 'tagservices:list']);
        } else {
            $repo = $em->getRepository('App\Entity\Category');
            $rootNode = $repo->findOneBy(['name' => 'Home']);
            /** @var ArrayCollection|Category[] $children */
            $children = $repo->getChildren($rootNode, true, 'name');
            $childrenEnabled = array_filter($children, function ($cat) {
                return $cat->getEnabled() === true;
            });
            $childrenEnabledFromZero = array_values($childrenEnabled);
            $jsonCat = $serializer->serialize($childrenEnabledFromZero, 'json', ['groups' => 'category:list']);
        }

        return $this->render('Front/categories_list/index.html.twig', [
            'categoriesJSON' => $jsonCat,
            'tagservicesJSON' => $jsonServices
        ]);
    }

    /**
     * @Route("/stare", name="app_delivered_orders")
     */
    public function oldOrders()
    {
        return $this->render('Front/categories_list/delivered_orders.html.twig');
    }

}