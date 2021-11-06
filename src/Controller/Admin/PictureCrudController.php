<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PictureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Picture::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            ImageField::new('pictureFile')
                ->setFormType(VichImageType::class),
            // TextareaField::new('pictureFile')
            //     ->setFormType(VichImageType::class),

            // ImageField::new('pictureFile')
            //     ->setFormType(VichFileType::class),

            ImageField::new('pictureFileName')
                ->hideOnForm()
                ->setBasePath("/pictures/contributions"),
            AssociationField::new('trick'),
            'alt'
        ];
    }
}
