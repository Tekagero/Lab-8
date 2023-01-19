<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\News;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 9; $i++) {

            $user = new User();
            $user->setName("Пользователь номер " . ($i+1));
            $user->setPassword(password_hash("password".($i+1), PASSWORD_DEFAULT));
            $user->setPhone("+7919240121".($i+1));
            $manager->persist($user);
            if ($i % 4 == 0) {
                $user->setRoles(["ROLE_ADMIN"]);
            }
            else {
                $user->setRoles(["ROLE_USER"]);
            }
            for ($j = 0; $j < 15; $j++) {

                $news = new News();
                $news->setName("Новость номер " . ($i+1) . ($j+1));
                $news->setAnnotation("Аннотация номер " . ($i+1) . ($j+1));
                if ($j < 9) {
                    $news->setDate(new \DateTime("2022-0" . ($i + 1) . "-0" . ($j + 1) . "T15:03:01.012345Z"));
                }
                else {
                    $news->setDate(new \DateTime("2022-0" . ($i + 1) . "-" . ($j + 1) . "T15:03:01.012345Z"));
                }
                $news->setText("Текст новости номер ". ($i+1) . ($j+1));
                $news->setViews(0);
                $news->setAuthor($user);
                $manager->persist($news);

                for ($k = 0; $k < 2; $k++) {
                    $comment = new Comment();
                    if ($j < 9) {
                        $comment->setDate(new \DateTime("2022-0".($k+1)."-0". ($j+1)."T15:03:01.012345Z"));
                    }
                    else {
                        $comment->setDate(new \DateTime("2022-0".($k+1)."-". ($j+1)."T15:03:01.012345Z"));
                    }
                    $comment->setText("Комментарий номер ". ($k+1));
                    $comment->setAuthor($user);
                    $comment->setNews($news);
                    if ($k == 1) {
                        $comment->setStatus(false);
                    }
                    else {
                        $comment->setStatus(true);
                    }
                    $manager->persist($comment);
                }

            }
        }


        $manager->flush();
    }
}
