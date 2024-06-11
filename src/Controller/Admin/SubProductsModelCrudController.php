<?php

namespace App\Controller\Admin;

use App\Entity\ModelSubProduct;
use App\Entity\SubProductsModel;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SubProductsModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ModelSubProduct::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id');
        $name = TextField::new('name');
        $price = NumberField::new('price');
        $status = BooleanField::new('status');
        $product = AssociationField::new('subProduct');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $product, $price, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$name, $price, $status];
        } else {
            return [$name, $price, $status, $product];
        }
    }
}
