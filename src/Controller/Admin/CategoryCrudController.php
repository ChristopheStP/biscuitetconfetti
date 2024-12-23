<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        $required = true;
        if ($pageName == 'edit') {
            $required = false;
        }

        return [
            TextField::new('name')->setLabel('Titre')->setHelp('Titre de la catégorie'),
            BooleanField::new('isHomepage')->setLabel('Collection à la une')->setHelp('Vous permet d\'afficher vos collections sur la page d\'accueil'),
            SlugField::new('slug')->setLabel('URL')->SETTargetFieldName('name')->setHelp('URL de votre catégorie générée automatiquement'),
            ImageField::new('illustration')
                ->setLabel('Image')
                ->setHelp('Image du produit en 600x600px')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/uploads')
                ->setUploadDir('public/uploads')
                ->setRequired($required),
        ];
    }

}
