<?php
namespace Page;

class SearchPage
{
    protected $tester;

    public $openInputFieldButton;

    public $queryField;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->openInputFieldButton = ["using" => "class name", "value" => "header__search"];
        $this->queryField = ["using" => "xpath", "value" => "//form[class='search-small-form']//input"];
    }

}
