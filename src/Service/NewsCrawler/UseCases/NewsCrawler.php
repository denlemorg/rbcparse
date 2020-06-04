<?php


namespace App\Service\NewsCrawler\UseCases;

class NewsCrawler
{
    /**
     * @var string[]
     */
    private $sourceList = [
        "Rbc"=>"App\Service\NewsCrawler\UseCases\LinksCollectors\RbcLinksCollector",
//        "Vedomosti"=>"App\Service\NewsCrawler\UseCases\LinksCollectors\VedomostiLinksCollector",
    ];

    /**
     * @return LinksCollector[]
     */
    public function run(): array
    {
        $news = [];
        foreach ($this->sourceList as $v) {
            if (class_exists($v)) {
                $news = array_merge($news, ( new $v() )->get());
            }
        }
        return $news;
    }
}