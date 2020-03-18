<?php


namespace App\Service\ParseNews;


use PHPHtmlParser\Dom;

class StyleItem extends AbstractParseManager
{
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
}