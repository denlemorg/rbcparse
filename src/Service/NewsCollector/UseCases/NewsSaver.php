<?php


namespace App\Service\NewsCollector\UseCases;

use App\Entity\Post;
use App\Service\NewsCollector\Parsers\MainNewsItem;
use Doctrine\ORM\EntityManagerInterface;

class NewsSaver
{
    private $postRepository;
    public function __construct(EntityManagerInterface $em)
    {
        $this->postRepository = $em->getRepository(Post::class);
    }

    /**
     * @param MainNewsItem[] $news
     */
    public function newsSave($news)
    {
        if (count($news)>0) {
            $this->postRepository->saveNewsWithChecking($news);
        }
    }
}
