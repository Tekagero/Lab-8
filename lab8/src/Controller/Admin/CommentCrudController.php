<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('date')->hideOnForm(),
            TextEditorField::new('text'),
            IdField::new('news.id'),
            TextField::new('news.name'),
            BooleanField::new('status')->setLabel('Одобрено модератором')
        ];
    }


    public function persistEntity (EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Comment) return;

        $entityInstance->setDate(new \DateTime('now'));

        parent::persistEntity($entityManager, $entityInstance);
    }


}
