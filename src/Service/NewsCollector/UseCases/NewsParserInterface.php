<?php

namespace App\Service\NewsCollector\UseCases;

use App\Service\NewsCollector\Parsers\MainNewsItem;
use \Psr\Http\Message\StreamInterface;

interface NewsParserInterface
{
    public function collectNewsFromSite(): ?array;
    public function isValidNewsItem(MainNewsItem $currentNewsItem): bool;
    public function getHTMLContent(string $siteUrl): StreamInterface;
}
