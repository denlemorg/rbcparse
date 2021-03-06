<?php


namespace App\Service\NewsCollector\Parsers\VedomostiParser;

use App\Service\NewsCollector\Parsers\MainNewsItem;
use App\Service\NewsCollector\Parsers\RbcParser\ParseScripts\ParseAgroItem ;
use App\Service\NewsCollector\Parsers\RbcParser\ParseScripts\ParseRegularItem;
use App\Service\NewsCollector\Parsers\RbcParser\ParseScripts\ParseStyleItem;
use App\Service\NewsCollector\UseCases\NewsParserAggregator;
use PHPHtmlParser\Dom;

class VedomostiParserList extends NewsParserAggregator
{
    private const SITE_URL = 'https://www.rbc.ru';
    private const LEFT_COLUMN_SELECTOR = '.news-feed__wrapper .js-news-feed-list';
    private const LIST_NEWS_SELECTOR = 'a.news-feed__item';
    private const DATE_SELECTOR = '.news-feed__item__date .news-feed__item__date-text';

    /**
     * Get news from site and return array of news MainNewsItem
     *
     * @return MainNewsItem[]|null
     *
     */
    public function collectNewsFromSite(): ?array
    {
        return [];
    }
}
