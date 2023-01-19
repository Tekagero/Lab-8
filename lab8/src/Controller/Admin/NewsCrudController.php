<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class NewsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return News::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('date')->hideOnForm(),
            TextEditorField::new('text'),
            TextEditorField::new('annotation'),
            TextField::new('name')
        ];
    }


    public function persistEntity (EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof News) return;

        $entityInstance->setDate(new \DateTime('now'));

        parent::persistEntity($entityManager, $entityInstance);
    }

}
