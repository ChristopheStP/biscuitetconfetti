<?php

namespace App\Controller\Admin;

use App\Entity\Body;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BodyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Body::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        $required = true;
        if ($pageName == 'edit') {
            $required = false;
        }

        return [
            TextField::new('title', 'title'),
            TextEditorField::new('content', 'content'),
            ImageField::new('illustration')
                ->setLabel('Image de fond du body')
                ->setHelp('Image de fond du body en JPEG')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/uploads')
                ->setUploadDir('public/uploads')
                ->setRequired($required),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Gestion du body')
            ->setPageTitle('new', 'Créer un body')
            ->setPageTitle('edit', 'Modifier un body');
    }
}
