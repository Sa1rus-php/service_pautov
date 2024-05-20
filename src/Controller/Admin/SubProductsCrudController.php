<?php

namespace App\Controller\Admin;

use App\Entity\SubProducts;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SubProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SubProducts::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id');
        $name = TextField::new('name');
        $description = TextField::new('description');
        $price = NumberField::new('price');
        $execution = NumberField::new('execution');
        $status = BooleanField::new('status');
        $imageFile = TextareaField::new('imageFile')->setFormType(VichImageType::class);
        $product = AssociationField::new('product');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $product, $description, $execution, $price, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$name, $description, $price, $execution, $status];
        } else {
            return [$name, $description, $execution, $price, $status, $imageFile, $product];
        }
    }
}
