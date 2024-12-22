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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig'])
            ->setPageTitle('index', 'Gestion du body')
            ->setPageTitle('new', 'Créer un body')
            ->setPageTitle('edit', 'Modifier un body');
    }
    
    public function configureFields(string $pageName): iterable
    {
        $required = true;
        if ($pageName == 'edit') {
            $required = false;
        }

        return [
            TextField::new('title', 'titre'),
            TextAreaField::new('content', 'Contenu')
                ->setFormTypeOptions([
                    'attr' => [
                        'class' => 'ckeditor',
                        'rows' => '10'
                    ],
                    'required' => true
                ])
                ->addCssClass('ckeditor-field'),
            ImageField::new('illustration')
                ->setLabel('Image de fond du body')
                ->setHelp('Image de fond du body en JPEG')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/uploads')
                ->setUploadDir('public/uploads')
                ->setRequired($required),
        ];
    }

    
}
