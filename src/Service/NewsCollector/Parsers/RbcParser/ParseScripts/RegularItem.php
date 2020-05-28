<?php

namespace App\Service\NewsCollector\Parsers\RbcParser\ParseScripts;

use PHPHtmlParser\Dom;
use App\Service\NewsCollector\Parsers\MainNewsItem;

class RegularItem extends MainNewsItem
{
    /**
     * @return void
     */
    public function parseNewsItem(): void
    {
        if ($this->dom->find(".js-rbcslider")[0]) {
            $this->parseTitle();
            $this->parseImg();
            $this->parseContent();
        } else {
            // сделать вывод логов подробно, что ошибка парсинга
            $this->title = $this->image = $this->body = "";
        }
    }

    /**
     * @return void
     */
    private function parseTitle(): void
    {
        try {
            $artTitle = $this->dom->find(".js-rbcslider")[0]->
                find('.article__header .article__header__title .js-slide-title')->innerHtml;
            $this->title = $artTitle;
        } catch (\Exception $e) {
            $this->title = "";
        }
    }

    /**
     * @return void
     */
    private function parseImg()
    {
        try {
            $artImg = $this->dom->find(".js-rbcslider")[0]->find('.article__text .article__main-image img');
            $this->image = $artImg->getAttribute('src');
        } catch (\Exception $e) {
            $this->image =  "";
        }
    }

    /**
     * @return void
     */
    private function parseContent()
    {
        try {
            $artContents = $this->dom->find(".js-rbcslider")[0]->find('.article .article__text');
            $html = "";
            $domContent = new Dom();
            foreach ($artContents as $content) {
                $domContent->load($content);
                $artStrings = $domContent->find("p");
                foreach ($artStrings as $string) {
                    $str = strip_tags($string->innerHtml);
                    $html .= "<br />" . trim($str);
                }
            }
            $this->body = $html;
        } catch (\Exception $e) {
            $this->body = "";
        }
    }
}
