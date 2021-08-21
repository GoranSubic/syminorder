<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\MyMoneType;
use App\Form\ProductPictureType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageFile = ImageField::new('picture')
            ->setFormType(ProductPictureType::class);
        $image = ImageField::new('picture.imageName')
            ->setBasePath('/images/products');
        $fields = [
            BooleanField::new('enabled', 'Uključeno'),
            TextField::new('name', 'Naziv'),
            TextField::new('slug'),
            TextField::new('code', 'Code'),
            TextEditorField::new('description', 'Opis')->hideOnIndex(),
            TextEditorField::new('long_description', 'Duži opis')->hideOnIndex(),
            MoneyField::new('price', 'Cena Money')
                ->setCurrency("RSD")
                ->setNumDecimals(2)
                ->setFormType(MoneyType::class)
            ,
            AssociationField::new('category', 'Kategorija')->autocomplete(),
            AssociationField::new('tagServices', 'Online ordinacija')
                ->hideOnDetail()
                ->autocomplete(),
//            BooleanField::new('showAdditions', 'Prikaži dodatke'),
        ];

        if ($pageName == Crud::PAGE_DETAIL) {
            $fields[] = ArrayField::new('tagServices', 'Online ordinacija');
        }

        if($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $image;
        } else {
            $fields[] = $imageFile;
        }
        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(CRUD::PAGE_INDEX, 'detail');
    }
}
