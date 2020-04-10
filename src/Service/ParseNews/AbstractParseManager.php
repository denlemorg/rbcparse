<?php


namespace App\Service\ParseNews;


use DateTime;
use PHPHtmlParser\Dom;

abstract class AbstractParseManager
{
    protected $title;
    protected $image;
    protected $body;
    protected $link;
    protected $date;
    protected $dom;

    /**
     * AbstractParseManager constructor.
     * @param Dom $dom
     * @param string $link
     * @param DateTime $date
     */
    public function __construct(Dom $dom, string $link,  DateTime $date){
        $this->link = $link;
        $this->dom = $dom;
        $this->date = $date;
    }

    /**
     * @return void
     */
    abstract public function parseNewsItem(): void ;

    /**
     * @return string|null
     */
    public function getTitle(){
        return $this->title;
    }
    /**
     * @return string|null
     */
    public function getBody(){
        return $this->body;
    }
    /**
     * @return string|null
     */
    public function getImage(){
        return $this->image;
    }
    /**
     * @return string|null
     */
    public function getLink(){
        return $this->link;
    }

    /**
     * @return DateTime
     */
    public function getDate(){
        return $this->date;
    }
}