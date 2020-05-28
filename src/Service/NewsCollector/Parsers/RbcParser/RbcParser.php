<?php


namespace App\Service\NewsCollector\Parsers\RbcParser;

use App\Service\NewsCollector\Parsers\MainNewsItem;
use App\Service\NewsCollector\Parsers\RbcParser\ParseScripts\AgroItem ;
use App\Service\NewsCollector\Parsers\RbcParser\ParseScripts\RegularItem;
use App\Service\NewsCollector\Parsers\RbcParser\ParseScripts\StyleItem;
use App\Service\NewsCollector\UseCases\NewsParserAggregator;
use PHPHtmlParser\Dom;

class RbcParser extends NewsParserAggregator
{
    private const SITE_URL = 'https://www.rbc.ru';
    private const LEFT_COLUMN_SELECTOR = '.news-feed__wrapper .js-news-feed-list';
    private const LIST_NEWS_SELECTOR = 'a.news-feed__item';
    private const DATE_SELECTOR = '.news-feed__item__date .news-feed__item__date-text';

    /**
     * Get news from site and return array of news MainNewsItem
     *
     * @return MainNewsItem[]|null
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function collectNewsFromSite(): ?array
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

            if ($this->isValidNewsItem($currentNewsItem)) {
                $resultNewsList[] = $currentNewsItem;
            }
        }
        return $resultNewsList;
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
