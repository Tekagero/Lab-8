<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\News;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Form\NewsFormType;
use App\Services\PageFormer;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;


class MainController extends AbstractController
{
     /**
     * @Route("/archive/{page}", name="archive_page", methods={"GET", "POST"})
     */
    public function archive(int $page, ManagerRegistry $doctrine): Response
    {
        $session = new Session();
        $session->start();

        $name = $session->get('name') ?? null;

        $news = $doctrine->getRepository(News::class)->getPageNews($page);

        $pages = PageFormer::formPagination($page, $doctrine);

        return $this->render('main/archive.html.twig', [
            'controller_name' => 'MainController',
            'news' => $news,
            'user_name' => $name,
            'actual_page' => $page,
            'pages' => $pages
        ]);
    }

     /**
     * @Route("/", name="app_main", methods={"GET", "POST"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $session = new Session();

        $name = $session->get('name') ?? null;

        $news = $doctrine->getRepository(News::class)->getLastNews();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'news' => $news,
            'user_name' => $name
        ]);
    }

     /**
     * @Route("/news/{id}", name="app_new", methods={"GET", "POST"})
     */
    public function news(News $news, ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $name = $session->get('name') ?? null;

        if ($news->getAuthor()->getId() != $this->container->get('security.token_storage')->getToken()->getUser()->getId()) {
            $news->setViews($news->getViews() + 1);
        }

        $comments = $doctrine->getRepository(Comment::class)->getCommentsById($news->getId());

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();
            $comment->setAuthor($doctrine->getRepository(User::class)->find(33));
            $comment->setDate(new \DateTime('now'));
            $comment->setNews($doctrine->getRepository(News::class)->find($news->getId()));
            $em = $doctrine->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($news->getId());
        }

        $entityManager = $doctrine->getManager();
        $entityManager->persist($news);
        $entityManager->flush();


        return $this->renderForm('main/news.html.twig', [
            'controller_name' => 'MainController',
            'news' => $news,
            'comments' => $comments,
            'user_name' => $name,
            'form' => $form
        ]);
    }

}
