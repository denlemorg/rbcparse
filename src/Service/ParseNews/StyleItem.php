<?php


namespace App\Service\ParseNews;


use PHPHtmlParser\Dom;

class StyleItem implements ParsedNewsInterface
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
            $artTitle = $this->dom->find(".rbcslider__slide")[0]->find('.article__header')->innerHtml;
            $this->title = trim($artTitle);
        }catch(\Exception $e){
            $this->title = "";
        }
    }

    private function parseImg(){
        try {
            $artImg = $this->dom->find(".rbcslider__slide")[0]->find('.article__main-image img');
            $this->image = $artImg->getAttribute('src');
        }catch(\Exception $e){
            $this->image = "";
        }
    }

    private function parseContent(){
        try {
            $artContents = $this->dom->find(".rbcslider__slide")[0]->find('.article__text p');
            $html = "";
            foreach ($artContents as $content)
            {
                $str = strip_tags($content->innerHtml);
                $html .= "<br />" . trim($str);
            }
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