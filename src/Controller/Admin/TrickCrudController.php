<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TrickCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trick::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            'name',
            TextField::new('description'),
            // TextEditorField::new('description'), //pour l'instant commenter car TextEditorField me rajoute des balise qui ne sont pas gerer dans mon code (controller et template)
            AssociationField::new('pool'),
            AssociationField::new('user'),
            // 'slug',
            // ImageField::new('pictures'),

            CollectionField::new('pictures')
                ->setEntryType(PictureEasyAdminType::class)
                ->onlyOnForms(),


            CollectionField::new('videos')
                ->setEntryType(VideoEasyadminType::class)
                // ->setLabel(source video (url/nom))
                ->onlyOnForms(),
            // ->setFormTypeOption('by_reference', false),
        ];
    }
}
