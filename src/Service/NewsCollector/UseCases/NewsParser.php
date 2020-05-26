<?php


namespace App\Service\NewsCollector\UseCases;

use App\Service\NewsCollector\ParseScripts\MainNewsItem;
use App\Service\NewsCollector\ParseScripts\AgroItem ;
use App\Service\NewsCollector\ParseScripts\RegularItem;
use App\Service\NewsCollector\ParseScripts\StyleItem;
use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

class NewsParser
{
    private const SITE_URL = 'https://www.rbc.ru';
    private const LEFT_COLUMN_SELECTOR = '.news-feed__wrapper .js-news-feed-list';
    private const LIST_NEWS_SELECTOR = 'a.news-feed__item';
    private const DATE_SELECTOR = '.news-feed__item__date .news-feed__item__date-text';
    private $client;
    /**
     * @param Client $client
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->dom = new Dom();
    }

    /**
     * Get news from site and return array of news MainNewsItem
     *
     * @return MainNewsItem[]
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function collectNewsFromSite(): array
    {
        $firstPageContent = $this->getHTMLContent(self::SITE_URL);
        $this->dom->load($firstPageContent);
        $contents = $this->dom->find(self::LEFT_COLUMN_SELECTOR)->find(self::LIST_NEWS_SELECTOR);
        $resultNewsList = [];

        foreach ($contents as $content) {
            $dateCurrentNews = $this->parseDateCurrentNews($content);
            $link = $content->getAttribute('href');

            $singleNewsContent = $this->getHTMLContent($link);

            $this->dom->load($singleNewsContent);

            if (preg_match("/style.rbc/", $link)) {
                $currentNewsItem = new StyleItem($this->dom, $link, $dateCurrentNews);
            } elseif (preg_match("/agrodigital.rbc/", $link)) {
                $currentNewsItem = new AgroItem($this->dom, $link, $dateCurrentNews);
            } elseif (preg_match("/healthindex.rbc/", $link)
                || preg_match("/gosmart.rbc/", $link)
                || preg_match("/savebusiness.rbc/", $link)
            ) {
                continue;
            } else {
                $currentNewsItem = new RegularItem($this->dom, $link, $dateCurrentNews);
            }
            $currentNewsItem->parseNewsItem();

            $resultNewsList[] = $currentNewsItem;
        }
        return $resultNewsList;
    }

    /**
     * @param $siteUrl
     * @return \Psr\Http\Message\StreamInterface
     */
    private function getHTMLContent($siteUrl)
    {
        $result = $this->client->request('GET', $siteUrl);
        $body = $result->getBody();
        return $body;
    }

    /**
     * Get date of news from left column
     *
     * @param Dom\HtmlNode $content
     * @return \DateTime
     */
    private function parseDateCurrentNews(Dom\HtmlNode $content): \DateTime
    {
        $dateInfo = $content->find(self::DATE_SELECTOR)->innerHTML;
        $time = explode("&nbsp;", $dateInfo)[1];
        $date = new \DateTime("now");
        $timeSet = explode(":", $time);
        $date->setTime((int)$timeSet[0], (int)$timeSet[1]);
        return $date;
    }
}
