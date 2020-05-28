<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Service\NewsCollector\NewsCollector;
use App\Service\ParseNews\RbcParseClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class PostController extends AbstractController
{
    /**
     * @var PostRepository
     */
    private $postRepository;

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
     * @param NewsCollector $newsCollector
     * @return Response
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */

    public function index(Request $request, RbcParseClient $parse, NewsCollector $newsCollector): Response
    {
        if ($request->query->get('update') && $request->query->get('update') == '1') {
//            $parse->updateNews();
            $newsCollector->updateNews();
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
