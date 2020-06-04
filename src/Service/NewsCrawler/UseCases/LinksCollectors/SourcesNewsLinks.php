<?php


namespace App\Service\NewsCrawler\UseCases\LinksCollectors;


class SourcesNewsLinks
{
    private $source;
    private $link;
    private $date;

    /**
     * SourcesNewsLinks constructor.
     * @param $source
     * @param $link
     * @param $date
     */
    public function __construct($source, $link, $date = null)
    {
        $this->source = $source;
        $this->link = $link;
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     * @return SourcesNewsLinks
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     * @return SourcesNewsLinks
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return SourcesNewsLinks
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
}