<?php

namespace App\Service\ParseNews;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use PHPHtmlParser\Dom;
use GuzzleHttp\Client;


class RbcParseClient
{
    private $dom;
    private $em;
    private $checkPosts;

    public function __construct(EntityManagerInterface $em)
    {
        $this->dom = new Dom();
        $this->em = $em;
        $postRepository = $this->em->getRepository(Post::class);
        $this->checkPosts = $postRepository->findLastNews();
    }


    private function getPostEntity(string $title): Post
    {
        $postObj = new Post();
        if (count($this->checkPosts) > 0){
            foreach($this->checkPosts as $checkPost){
                if ($checkPost->getTitle() == $title){
                    $postObj = $checkPost;
                    break;
                }
            }
        }
        return $postObj;
    }

    public function updateNews(): void
    {
        $res = $this->getUrlContent('https://www.rbc.ru');
        $this->dom->load($res);
        $contents = $this->dom->find('.news-feed__wrapper .js-news-feed-list')->find('a.news-feed__item');

        foreach ($contents as $content)
        {
            $dateInfo = $content
                ->find('.news-feed__item__date .news-feed__item__date-text')->innerHTML;
            $time = explode("&nbsp;", $dateInfo)[1];

            $date = new \DateTime("now");
            $timeSet = explode(":",$time);
            $date->setTime((int)$timeSet[0], (int)$timeSet[1]);

            $link = $content->getAttribute('href');

            $resArt = $this->getUrlContent($link);
            $this->dom->load($resArt);

            if (preg_match("/style.rbc/", $link) ) {
                $currentNewsItem = new StyleItem($this->dom, $link, $date);
            }elseif(preg_match("/agrodigital.rbc/", $link)){
                $currentNewsItem = new AgroItem($this->dom, $link, $date);
//            }elseif(preg_match("/plus.rbc/", $link) || preg_match("/sport.rbc/", $link) || preg_match("/pro.rbc/", $link)){
//                continue;
            }else {
                $currentNewsItem = new RegularItem($this->dom, $link, $date);
            }
            $currentNewsItem->parseNewsItem($link);

            $post = $this->getPostEntity($currentNewsItem->getTitle());
            $date = $currentNewsItem->getDate();
            $position = $date->format('U');

            $post->setTitle($currentNewsItem->getTitle());
            $post->setBody($currentNewsItem->getBody());
            $post->setImage($currentNewsItem->getImage());
            $post->setLink($currentNewsItem->getLink());
            $post->setCreatedAt($date);
            $post->setPosition($position);

            $this->em->persist($post);
        }
        $this->em->flush();
    }

    private function getUrlContent($url)
    {
        $client = new Client([ 'base_uri' => $url ]);
        $response = $client->request('GET');
        $res = $response->getBody();

        return $res;
    }
}