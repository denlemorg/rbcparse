<?php


namespace App\Service\ParseNews;


use PHPHtmlParser\Dom;

class StyleItem extends AbstractParseManager
{
    /**
     * @return void
     */
    public function parseNewsItem(): void
    {
        if ($this->dom->find(".rbcslider__slide")[0]) {
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
            $artTitle = $this->dom->find(".rbcslider__slide")[0]->find('.article__header')->innerHtml;
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
            $artImg = $this->dom->find(".rbcslider__slide")[0]->find('.article__main-image img');
            $this->image = $artImg->getAttribute('src');
        }catch(\Exception $e){
            $this->image = "";
        }
    }
    /**
     * @return void
     */
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