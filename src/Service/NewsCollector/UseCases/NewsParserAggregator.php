<?php


namespace App\Service\NewsCollector\UseCases;

use Psr\Http\Message\StreamInterface;
use App\Service\NewsCollector\Parsers\MainNewsItem;
use App\Service\NewsCollector\UseCases\NewsParserInterface;
// use App\Service\NewsCollector\Parsers\RbcParser\RbcParser;
use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

class NewsParserAggregator implements NewsParserInterface
{
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var Dom
     */
    protected $dom;

    /**
     * @var string[]
     */
    private $parsers = [
                "RbcParser"=>"App\Service\NewsCollector\Parsers\RbcParser\RbcParser",
                "VedomostiParser"=>"App\Service\NewsCollector\Parsers\VedomostiParser\VedomostiParser",
            ];

    /**
     * @param Client $client
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->dom = new Dom();
    }

    /**
     * @return MainNewsItem[]
     */
    public function run(): array
    {
        $news = [];
        foreach ($this->parsers as $v) {
            if (class_exists($v)) {
                $news = array_merge($news, ( new $v() )->collectNewsFromSite());
            }
        }
//        dd($news);
        return $news;
    }

    /**
     * Get news from site and return array of news MainNewsItem
     *
     * @return MainNewsItem[]|null
     */
    public function collectNewsFromSite(): ?array
    {
        return null;
    }

    /**
     * @param MainNewsItem $currentNewsItem
     * @return bool
     */
    public function isValidNewsItem(MainNewsItem $currentNewsItem): bool
    {
        if (!($currentNewsItem->getTitle() == '' &&
            $currentNewsItem->getBody() == '' && $currentNewsItem->getImage() == '')) {
            return true;
        }
        return false;
    }
    /**
     * @param $siteUrl
     * @return StreamInterface
     */
    public function getHTMLContent(string $siteUrl): StreamInterface
    {
        $result = $this->client->request('GET', $siteUrl);
        $body = $result->getBody();
        return $body;
    }
}
