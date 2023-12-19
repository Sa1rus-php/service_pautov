<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id')->hideOnForm();
        $user = AssociationField::new('user');
        $grade = IntegerField::new('grade');
        $title = TextField::new('title');
        $body = TextField::new('body');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $user, $title, $grade];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $user, $title, $grade];
        } else {
            return [$id, $user, $title, $body, $grade];
        }
    }
}
