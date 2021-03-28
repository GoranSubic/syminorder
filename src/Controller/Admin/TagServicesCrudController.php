<?php

namespace App\Controller\Admin;

use App\Entity\TagServices;
use App\Form\ProductPictureType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TagServices::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageFile = ImageField::new('picture')
            ->setFormType(ProductPictureType::class);
        $image = ImageField::new('picture.imageName')
            ->setBasePath('/images/products');
        $fields = [
            BooleanField::new('enabled'),
            TextField::new('name'),
            TextField::new('slug'),
            TextEditorField::new('description'),
            TextEditorField::new('long_description'),
            AssociationField::new('products', 'Proizvodi')->hideOnForm(),
        ];

        if($pageName == Crud::PAGE_DETAIL) {
            $fields[] = ArrayField::new('products', 'Proizvodi');
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
