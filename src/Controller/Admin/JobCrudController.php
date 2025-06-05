<?php

namespace App\Controller\Admin;

use App\Entity\Job;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Job::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            TextareaField::new('description', 'Description'),
            TextField::new('country', 'Pays'),
            TextField::new('city', 'Ville'),
            BooleanField::new('remoteAllowed', 'Télétravail autorisé ?'),
            NumberField::new('salaryRange', 'Salaire (ex: 40000)'),
            
            // Relations
            AssociationField::new('company', 'Entreprise'),
            AssociationField::new('jobType', 'Type de contrat'),
            AssociationField::new('categories', 'Catégories')->autocomplete()->setFormTypeOptions([
                'by_reference' => false,
            ]),
        ];
    }
    
}
