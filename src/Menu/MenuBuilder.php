<?php


namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuBuilder
{
    private $factory;
    private $translator;

    public function __construct(FactoryInterface $factory, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(['class' => 'navbar-nav mr-auto']);

        $menu->addChild('Home', [
                'route' => 'homepage',
                'label' => $this->translator->trans('navbar.brand'),
                'attributes' => ['class' => 'nav-item homepage active']
            ],);
        $menu['Home']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('Offer', [
            'route' => 'app_orders',
            'label' => $this->translator->trans('navbar.offer'),
            'attributes' => ['class' => 'nav-item offer']
        ]);
        $menu['Offer']->setLinkAttribute('class', 'nav-link');

        $menu->addChild('About', [
            'route' => 'app_static_about',
            'label' => $this->translator->trans('navbar.about'),
            'attributes' => ['class' => 'nav-item about']
        ]);
        $menu['About']->setLinkAttribute('class', 'nav-link');

        return $menu;
    }
}