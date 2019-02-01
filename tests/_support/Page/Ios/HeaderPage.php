<?php
namespace Page\Ios;

class HeaderPage
{
    protected $tester;

    public $root;

    public $mainLogo;
    public $fullSiteButton;

    public $basketButton;
    public $totalQuantityCircle;

    public $searchOpenInputFieldButton;
    public $searchQueryField;
    public $searchButton;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ['using' => 'class name', 'value' => 'header__main'];

        $this->mainLogo = ['using' => 'class name', 'value' => 'logo'];
        $this->fullSiteButton = ['using' => 'class name', 'value' => 'adptr__backlink'];

        $this->totalQuantityCircle = ['using' => 'class name', 'value' => 'quantity-badge'];
        $this->basketButton = ['using' => 'class name', 'value' => 'header__cart'];

        $this->searchOpenInputFieldButton = ["using" => "class name", "value" => "header__search"];
        $this->searchQueryField = ["using" => "xpath", "value" => "//form[@class='search-small-form']//input"];
        $this->searchButton = ["using" => "xpath", "value" => "//form[@class='search-small-form']//button[@class='search-button']"];
    }

    /**
     * Переход в корзину
     */
    public function goToCart()
    {
        $I = $this->tester;

        $oldUrl = $I->getRelativeUrl();
        $I->findBy($this->basketButton)->click();
        $I->waitUrlChange($oldUrl);
    }

    /**
     * Поиск по запросу
     * @param string $request
     */
    public function searchByRequest($request)
    {
        $I = $this->tester;

        $I->findBy($this->searchOpenInputFieldButton)->click();
        $I->findBy($this->searchQueryField)->value($request);
        $I->findBy($this->searchButton)->click();
    }
}
