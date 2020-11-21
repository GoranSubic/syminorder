<?php

namespace App\Controller\Admin;

use App\Entity\ProductPicture;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductPictureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductPicture::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('imageName'),
//            TextField::new('updatedAt'),
            ImageField::new('imageFile')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
            ImageField::new('imageName')
                ->setBasePath('/images/products')
                ->onlyOnIndex(),
        ];
    }
    
}
