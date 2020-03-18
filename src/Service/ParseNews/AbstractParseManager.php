<?php


namespace App\Service\ParseNews;


use PHPHtmlParser\Dom;

abstract class AbstractParseManager
{
    protected $title;
    protected $image;
    protected $body;
    protected $link;
    protected $date;
    protected $dom;

    public function __construct(Dom $dom, $link, $date){
        $this->link = $link;
        $this->dom = $dom;
        $this->date = $date;
    }
    abstract public function parseNewsItem();

    public function setLink($link){
        $this->link = $link;
    }
    public function getTitle(){
        return $this->title;
    }
    public function getImage(){
        return $this->image;
    }
    public function getBody(){
        return $this->body;
    }
    public function getLink(){
        return $this->body;
    }
    public function getDate(){
        return $this->date;
    }
}