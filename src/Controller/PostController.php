<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Service\ParseNews\RbcParseClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class PostController extends AbstractController
{
    /**
     * @param PostRepository $postRepository
     * @return void
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/", name="posts")
     * @param Request $request
     * @param RbcParseClient $parse
     * @return Response
     */

    public function index(Request $request, RbcParseClient $parse): Response
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
     * @param Post $post
     * @return Response
     */
    public function post(Post $post): Response
    {
        return $this->render('post/postshow.html.twig', [
            'post' => $post
        ]);
    }
}
