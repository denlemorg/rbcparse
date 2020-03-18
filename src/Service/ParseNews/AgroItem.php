<?php


namespace App\Service\ParseNews;


use PHPHtmlParser\Dom;

class AgroItem implements ParsedNewsInterface
{
    private $title;
    private $image;
    private $body;
    private $link;
    private $date;
    private $dom;

    public function __construct(Dom $dom, $link, $date){
        $this->link = $link;
        $this->dom = $dom;
        $this->date = $date;
    }
    public function parseNewsItem(){
        $this->parseTitle();
        $this->parseImg();
        $this->parseContent();
    }

    private function parseTitle(){
        try {
            $artTitle = $this->dom->find(".home__header")[0]->find('.home__title')->innerHtml;
            $this->title = trim($artTitle);
        }catch(\Exception $e){
            $this->title = "";
        }
    }

    private function parseImg(){
        try {
            $artImg = $this->dom->find(".home__header")[0]->find('.home__image img.for-desktop');
            $this->image = "http://agrodigital.rbc.ru". $artImg->getAttribute('src');
        }catch(\Exception $e){
            $this->image =  "";
        }
    }

    private function parseContent(){
        try {
            $artContents = $this->dom->find(".home__header")[0]->find('.home__lead');
            $str = strip_tags($artContents->innerHtml);
            $html = trim($str);

            $this->body = $html;
        }catch(\Exception $e){
            $this->body = "";
        }
    }

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