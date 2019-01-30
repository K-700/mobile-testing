<?php
namespace Page\Menu;

class MenuPage
{
    protected $tester;

    public $menuButton;

    public $backArrow;

    public $closeButton;

    public $navItem;

    public $rightArrow;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->menuButton = ["using" => "class name", "value" => "menu-burger"];
        $this->backArrow = ["using" => "class name", "value" => "header-nav__back"];
        $this->closeButton = ["using" => "class name", "value" => "header-nav__close"];
        $this->navItem = ["using" => "class name", "value" => "catalog-menu__core-title"];
        $this->rightArrow = ["using" => "css selector", "value" => "div.catalog-menu__core-title::after"];
    }

    public function openMenu()
    {
        $I = $this->tester;

        $I->findBy($this->menuButton)->click();
    }

    public function closeMenu()
    {
        $I = $this->tester;

        $I->findBy($this->closeButton)->click();
    }

    public function back()
    {
        $I = $this->tester;

        $I->findBy($this->backArrow)->click();
    }

    public function checkNavsRecursively()
    {
        $I = $this->tester;

        $navItems = $I->findElementsBy($this->navItem);
        $I->assertGreaterThan(0, count($navItems));
        foreach ($navItems as $navItem) {
            if ($I->findElementFromElementBy($navItem, $this->rightArrow)->displayed()) {
                $navItem->click();
                $this->checkNavsRecursively();
                $this->back();
            }
        }
    }
}
