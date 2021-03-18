<?php


namespace App\Menu;

use App\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuBuilder
{
    private $factory;
    private $entityManager;
    private $translator;

    public function __construct(FactoryInterface $factory, EntityManagerInterface $entityManager, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        /* Get Main Categories List */
        $repo = $this->entityManager->getRepository('App\Entity\Category');
        $rootNode = $repo->findOneBy(['name' => 'Home']);
        /** @var ArrayCollection|Category[] $children */
        $children = $repo->getChildren($rootNode, true, 'position', 'desc');
        $childrenEnabled = array_filter($children, function ($cat) {
            return $cat->getEnabled() === true && $cat->getShowOnFront() === true;
        });
        $childrenEnabledFromZero = array_values($childrenEnabled);

        /* Menu Root */
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'navbar-nav mr-auto']);

        /*$menu->addChild('Home', [
                'route' => 'homepage',
                'label' => $this->translator->trans('navbar.brand'),
                'attributes' => ['class' => 'nav-item homepage active']
            ])
            ->setLinkAttribute('class', 'nav-link');*/

        /* Main Categories Menu */
        $menu->addChild('Categories', [
            'label' => $this->translator->trans('navbar.offer'),
            'attributes' => ['class' => 'nav-item categories']
        ],)
            ->setAttribute('dropdown', true)
//            ->setAttribute('icon', 'fa fa-note')
            ->setLabelAttributes(['class' => 'nav-link dropdown-toggle', 'data-toggle' => 'dropdown'])
            ->setChildrenAttributes(['class' => 'ul-categories dropdown-menu']);

        /* Main Categories List */
        foreach ($childrenEnabledFromZero as $category) {
            $menu['Categories']->addChild($category->getName(), [
                'route' => 'category_show_front',
                'routeParameters' => ['slug' => $category->getSlug()],
                'label' => $category->getName(),
                'attributes' => ['class' => 'nav-item li-categories'],
            ]);
        }

        /* Indications */
        $menu->addChild('Indications', [
            'route' => 'app_inications',
            'label' => $this->translator->trans('navbar.indications'),
            'attributes' => ['class' => 'nav-item indications']
        ]);
        $menu['Indications']->setLinkAttribute('class', 'nav-link');

        /* About */
        $menu->addChild('About', [
            'route' => 'app_static_about',
            'label' => $this->translator->trans('navbar.about'),
            'attributes' => ['class' => 'nav-item about']
        ]);
        $menu['About']->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}