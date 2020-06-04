<?php


namespace App\Service\NewsCrawler;


use App\Service\NewsCrawler\UseCases\NewsCrawler;

class Handler
{

    /**
     * @var NewsCrawler
     */
    private $newsCrawler;

    /**
     * NewsCollector constructor.
     * @param NewsCrawler $newsCrawler
     */
    public function __construct(NewsCrawler $newsCrawler)
    {
        $this->newsCrawler = $newsCrawler;
    }

    /**
     *
     */
    public function collectNews(): void
    {
        $news = $this->newsCrawler->run();
    }
}