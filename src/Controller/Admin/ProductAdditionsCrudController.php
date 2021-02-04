<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductAdditions;
use App\Form\MyMoneType;
use App\Form\ProductPictureType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductAdditionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductAdditions::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            BooleanField::new('enabled', 'Uključeno'),
            TextField::new('name', 'Naziv'),
            TextField::new('code', 'Code'),
            MoneyField::new('price', 'Cena Money')
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
