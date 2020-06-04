<?php


namespace App\Service\NewsCrawler\UseCases\LinksCollectors\RbcLinksCollector;


use App\Service\NewsCrawler\UseCases\LinksCollector;
use App\Service\NewsCrawler\UseCases\LinksCollectors\SourcesNewsLinks;
use App\Service\NewsCrawler\UseCases\Service\PageContentGetter;
use PHPHtmlParser\Dom;

class RbcLinksCollector implements LinksCollector
{
    private const SOURCE = 'rbc.ru';
    private const SITE_URL = 'https://www.rbc.ru';
    private const LEFT_COLUMN_SELECTOR = '.news-feed__wrapper .js-news-feed-list';
    private const LIST_NEWS_SELECTOR = 'a.news-feed__item';
    private const DATE_SELECTOR = '.news-feed__item__date .news-feed__item__date-text';

    private $dom;
    private $contentGetter;

    public function __construct(Dom $dom, PageContentGetter $contentGetter)
    {
        $this->dom = $dom;
        $this->contentGetter = $contentGetter;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $firstPageContent = $this->contentGetter->getHTMLContent(self::SITE_URL);
        $this->dom->load($firstPageContent);
        $contents = $this->dom->find(self::LEFT_COLUMN_SELECTOR)->find(self::LIST_NEWS_SELECTOR);
        $resultNewsList = [];

        foreach ($contents as $content) {
            $date = $this->parseDateCurrentNews($content);
            $link = $content->getAttribute('href');
            $resultNewsList[] = new SourcesNewsLinks(self::SOURCE, $link, $date);
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