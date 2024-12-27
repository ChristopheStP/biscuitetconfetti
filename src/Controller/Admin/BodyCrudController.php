<?php

namespace App\Controller\Admin;

use App\Entity\Body;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BodyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Body::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $required = false;
        if ($pageName == 'edit') {
            $required = false;
        }

        return [
            TextField::new('title', 'title'),
            TextAreaField::new('content', 'Contenu')
                ->setFormTypeOptions([
                    'attr' => [
                        'class' => 'ckeditor',
                        'rows' => '10'
                    ],
                    'required' => false
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
