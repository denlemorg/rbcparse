<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\ParseNews\RbcParseClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class PostController extends AbstractController
{
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/", name="posts")
     */
    public function index(Request $request, RbcParseClient $parse)
    {
        if ($request->query->get('update') && $request->query->get('update') == '1'){
            $parse->updateNews();
        }
        $posts = $this->postRepository->findLastNews();
        return $this->render('post/posts.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/posts/{id}", name="post_show")
     */
    public function post(Post $post)
    {
        return $this->render('post/postshow.html.twig', [
            'post' => $post
        ]);
    }
}
