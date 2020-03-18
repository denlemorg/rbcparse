<?php


namespace App\Service\ParseNews;


use PHPHtmlParser\Dom;

class RegularItem extends AbstractParseManager
{
    public function parseNewsItem(){
        $this->parseTitle();
        $this->parseImg();
        $this->parseContent();
    }
    private function parseTitle(){
        try {
            $artTitle = $this->dom->find(".js-rbcslider")[0]->find('.article__header .article__header__title .js-slide-title')->innerHtml;
            $this->title = $artTitle;
        }catch(\Exception $e){
            $this->title = "";
        }
    }

    private function parseImg(){
        try {
            $artImg = $this->dom->find(".js-rbcslider")[0]->find('.article__text .article__main-image img');
            $this->image = $artImg->getAttribute('src');
        }catch(\Exception $e){
            $this->image =  "";
        }
    }

    private function parseContent(){
        try {
            $artContents = $this->dom->find(".js-rbcslider")[0]->find('.article .article__text');
            $html = "";
            $domContent = new Dom();
            foreach ($artContents as $content)
            {
                $domContent->load($content);
                $artStrings = $domContent->find("p");
                foreach ($artStrings as $string) {
                    $str = strip_tags($string->innerHtml);
                    $html .= "<br />" . trim($str);
                }
            }
            $this->body = $html;
        }catch(\Exception $e){
            $this->body = "";
        }
    }

}