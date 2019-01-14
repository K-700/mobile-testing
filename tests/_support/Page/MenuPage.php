<?php
namespace Page;

class MenuPage
{
    protected $tester;

    public $menuButton;

    public $mainMenuTitle;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->menuButton = ["using" => "class name", "value" => "menu-burger"];
        $this->mainMenuTitle = ["using" => "xpath", "value" => "//div[@class='main-menu-links']//span[@class='title']"];
    }

}
