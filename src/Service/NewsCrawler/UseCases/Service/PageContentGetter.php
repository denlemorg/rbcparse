<?php


namespace App\Service\NewsCrawler\UseCases\Service;


use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class PageContentGetter
{
    private $client;
    /**
     * @param Client $client
     */
    public function __construct()
    {
        $this->client = new Client();
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