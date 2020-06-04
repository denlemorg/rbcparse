<?php


namespace App\Service\NewsCrawler\UseCases;


interface LinksCollector
{
    public function get(): array;
}