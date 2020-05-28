<?php


namespace App\Service\NewsCollector;

use App\Service\NewsCollector\UseCases\NewsParserAggregator;
use App\Service\NewsCollector\UseCases\NewsSaver;

class NewsCollector
{
    /**
     * @var NewsSaver
     */
    private $newsSaver;
    /**
     * @var NewsParserAggregator
     */
    private $newsParserAggregator;

    /**
     * NewsCollector constructor.
     * @param NewsParserAggregator $newsParserAggregator
     * @param NewsSaver $newsSaver
     */
    public function __construct(NewsParserAggregator $newsParserAggregator, NewsSaver $newsSaver)
    {
        $this->newsParserAggregator = $newsParserAggregator;
        $this->newsSaver = $newsSaver;
    }

    /**
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function collectNews(): void
    {
        $news = $this->newsParserAggregator->run();
        $this->newsSaver->newsSave($news);
    }
}
