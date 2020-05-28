<?php


namespace App\Service\NewsCollector\Parsers;

use PHPHtmlParser\Dom;

abstract class MainNewsItem
{
    protected $title;
    protected $image;
    protected $body;
    protected $link;
    protected $date;
    protected $dom;
    protected $position;

    /**
     * AbstractParseManager constructor.
     * @param Dom $dom
     * @param string $link
     */
    public function __construct(Dom $dom, string $link, \DateTime $date)
    {
        $this->link = $link;
        $this->dom = $dom;
        $this->date = $date;
        $this->position = $date->format('U');
    }

    /**
     * @return void
     */
    abstract public function parseNewsItem(): void;

    /**
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * @return string|null
     */
    public function getBody()
    {
        return $this->body;
    }
    /**
     * @return string|null
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @return string|null
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string|null
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param \DateTime $date
     * @return void
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }
}
