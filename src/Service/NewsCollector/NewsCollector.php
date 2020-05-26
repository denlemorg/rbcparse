<?php


namespace App\Service\NewsCollector;

use App\Service\NewsCollector\UseCases\NewsParser;
use App\Service\NewsCollector\UseCases\NewsSaver;

class NewsCollector
{
    private $newsCollector;
    private $newsSaver;

    /**
     * NewsCollector constructor.
     * @param \App\Service\NewsCollector\NewsParser $newsCollector
     * @param \App\Service\NewsCollector\NewsSaver $newsSaver
     */
    public function __construct(NewsParser $newsCollector, NewsSaver $newsSaver)
    {
        $this->newsCollector = $newsCollector;
        $this->newsSaver = $newsSaver;
    }

    /**
     * @throws \PHPHtmlParser\Exceptions\ChildNotFoundException
     * @throws \PHPHtmlParser\Exceptions\CircularException
     * @throws \PHPHtmlParser\Exceptions\CurlException
     * @throws \PHPHtmlParser\Exceptions\NotLoadedException
     * @throws \PHPHtmlParser\Exceptions\StrictException
     */
    public function updateNews(): void
    {
        $news = $this->newsCollector->collectNewsFromSite();
        $this->newsSaver->newsSave($news);
    }
}
