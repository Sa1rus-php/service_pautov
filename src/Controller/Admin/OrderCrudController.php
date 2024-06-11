<?php

namespace App\Controller\Admin;

use App\Entity\Order;
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

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id')->hideOnForm();
        $user = AssociationField::new('user');
        $product = AssociationField::new('product');
        $subProduct = AssociationField::new('subProduct');
        $modelSubProduct = AssociationField::new('modelSubProduct');
        $status = BooleanField::new('status');


        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $user, $product, $subProduct, $modelSubProduct, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $user, $product, $subProduct, $modelSubProduct, $status];
        } else {
            return [$id, $user, $product, $subProduct, $modelSubProduct, $status];
        }
    }
}
