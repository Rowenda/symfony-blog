<?php

namespace App\Controller\Admin;

use App\Repository\PostRepository;
use App\Repository\CommentRepository ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.index")
     * @param PostRepository $postRepository
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function index(PostRepository $postRepository, CommentRepository $commentRepository): Response
    {
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC']);
        $comment = $commentRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'posts' => $posts,
            'comments' => $comment
        ]);
    }
}
