<?php

namespace App\Service\ParseNews;

use Doctrine\ORM\EntityManagerInterface;
use PHPHtmlParser\Dom;
use GuzzleHttp\Client;


class RbcParseClient
{
    private $dom;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->dom = new Dom();
        $this->em = $em;
    }

    public function inputNews(AbstractParseManager $currentNewsItem){

        $sql = '
            REPLACE INTO
                post (title, body, image, link, created_at)
            VALUES
                (:title, :body, :image, :link, :created_at)
        ';

//        $date = new \DateTime("now");
        $date = $currentNewsItem->getDate();
        $created_at = $date->format('Y-m-d H:i:s');

        $this->em->getConnection()
            ->prepare($sql)
            ->execute([
                'title' => $currentNewsItem->getTitle(),
                'body' => $currentNewsItem->getBody(),
                'image' => $currentNewsItem->getImage(),
                'link' => $currentNewsItem->getLink(),
                'created_at' => $created_at
            ]);
    }

    public function updateNews(){
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
            $date->setTime($timeSet[0], $timeSet[1]);

            $link = $content->getAttribute('href');

//            $link = 'http://agrodigital.rbc.ru/?utm_source=rbc&utm_medium=main&utm_campaign=rsh20w-r-startupd-m';
//            $link = 'https://realty.rbc.ru/news/5e71c0389a79477d27e0a3b8';

            $resArt = $this->getUrlContent($link);
            $this->dom->load($resArt);

            if (preg_match("/style.rbc/", $link) ) {
                $currentNewsItem = new StyleItem($this->dom, $link, $date);
            }elseif(preg_match("/agrodigital.rbc/", $link)){
                $currentNewsItem = new AgroItem($this->dom, $link, $date);
            }else {
                $currentNewsItem = new RegularItem($this->dom, $link, $date);
            }
            $currentNewsItem->parseNewsItem($link);

//            print $currentNewsItem->getTitle() . "<br />";
            $this->inputNews($currentNewsItem);

        }
    }

    private function getUrlContent($url){

        $client = new Client([ 'base_uri' => $url ]);
        $response = $client->request('GET');
        $res = $response->getBody();

//        $ch = curl_init('https://www.rbc.ru');
//        $ch = curl_init($url);
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt($ch, CURLOPT_HTTPHEADER,
//            [
//                'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
//                'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 YaBrowser/19.12.0.769 Yowser/2.5 Safari/537.36'
//            ]);
//        $res = curl_exec($ch);

        return $res;
    }
}