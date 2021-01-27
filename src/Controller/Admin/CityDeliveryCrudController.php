<?php

namespace App\Controller\Admin;

use App\Entity\CityDelivery;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class CityDeliveryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CityDelivery::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IntegerField::new('position', 'Pozicija'),
            TextField::new('name', 'Naziv'),
            NumberField::new('postalCode', 'Poštanski broj'),
            TextField::new('address', 'Adresa za lično preuzimanje'),
            MoneyField::new('price', 'Cena')
                ->setCurrency("RSD")
                ->setNumDecimals(2)
                ->setFormType(MoneyType::class)
            ,
            MoneyField::new('deliveryFree', 'Besplatna dostava')
                ->setCurrency("RSD")
                ->setNumDecimals(2)
                ->setFormType(MoneyType::class)
            ,
        ];

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(CRUD::PAGE_INDEX, 'detail');
    }
}
