<?php
namespace Page\Menu;

class CategoriesPage extends MenuPage
{
    public $categories;

    public $currentCategoryTitle;

    public function __construct(\IosTester $I)
    {
        parent::__construct($I);

        $this->categories = ["using" => "css selector", "value" => ".main-menu-links span"];
        $this->currentCategoryTitle = ["using" => "class name", "value" => "catalog-menu__link-title"];
    }
}
