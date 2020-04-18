<?php


namespace App\Service\ParseNews;


use PHPHtmlParser\Dom;

class AgroItem extends AbstractParseManager
{
    /**
     * @return void
     */
    public function parseNewsItem(): void
    {
        if ($this->dom->find(".home__header")[0]) {
            $this->parseTitle();
            $this->parseImg();
            $this->parseContent();
        }else{
            // сделать вывод логов подробно, что ошибка парсинга
            $this->title = $this->image = $this->body = "";
        }
    }

    /**
     * @return void
     */
    private function parseTitle(){
        try {
            $artTitle = $this->dom->find(".home__header")[0]->find('.home__title')->innerHtml;
            $this->title = trim($artTitle);
        }catch(\Exception $e){
            $this->title = "";
        }
    }

    /**
     * @return void
     */
    private function parseImg(){
        try {
            $artImg = $this->dom->find(".home__header")[0]->find('.home__image img.for-desktop');
            $this->image = "http://agrodigital.rbc.ru". $artImg->getAttribute('src');
        }catch(\Exception $e){
            $this->image =  "";
        }
    }

    /**
     * @return void
     */
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
}