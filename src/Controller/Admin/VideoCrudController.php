<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VideoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Video::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            'videoFileName',
            AssociationField::new('trick'),
        ];
    }
}
