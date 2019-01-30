<?php
namespace Page\Menu\Navs\NewsNav;

class NewsPage
{
    /** @var \IosTester */
    protected $tester;

    public $url;

    /** @var string Root locator */
    public $root;

    public $newsList;

    public $newsContainer;

    public $newsTitle;

    public $newsDate;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->url = '/news/';
        $this->root = ["using" => "class name", "value" => "text_content"];
        $this->newsList = ["using" => "class name", "value" => "news-list"];
        $this->newsContainer = ["using" => "xpath", "value" => "//dd"];
        $this->newsTitle = ["using" => "class name", "value" => "news-title"];
        $this->newsDate = ["using" => "class name", "value" => "news-datetime"];
    }
}