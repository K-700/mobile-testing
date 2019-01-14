<?php
namespace Page\Search;

use Page\ShopItem\ShopItemPage;

class SearchPage
{
    protected $tester;

    private $openInputFieldButton;

    private $queryField;

    private $searchButton;

    private $foundItemsResultInfo;

    private $infoSuggestion;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->openInputFieldButton = ["using" => "class name", "value" => "header__search"];
        $this->queryField = ["using" => "xpath", "value" => "//form[@class='search-small-form']//input"];
        $this->searchButton = ["using" => "xpath", "value" => "//form[@class='search-small-form']//button[@class='search-button']"];
        $this->foundItemsResultInfo = ["using" => "xpath", "value" => "//div[@class='search-page-info-result']"];
        $this->infoSuggestion = ["using" => "class name", "value" => "search-page-info-suggestion"];
    }

    public function searchByRequest($request)
    {
        $I = $this->tester;

        codecept_debug($this->openInputFieldButton);
//        $this->openInputFieldButton->click();
        $I->pauseExecution();
        $I->by($this->queryField)->value($request);
        $I->by($this->searchButton)->click();
    }

    public function checkFoundItems($regexp)
    {
        $I = $this->tester;
        $shopItemPage = new ShopItemPage($I);

        $I->waitForElementVisible($shopItemPage->root, 20);
        $foundShopItems = $I->findElementsBy($shopItemPage->root);
        $I->assertGreaterThanOrEqual(1, count($foundShopItems));
        $I->assertGreaterThanOrEqual(1, $I->grabIntFromString($I->by($this->foundItemsResultInfo)->text()));
        foreach ($foundShopItems as $shopItem) {
            $I->assertRegExp("/$regexp/ui", $I->findElementFromElementBy($shopItem, $shopItemPage->name)->text());
        }
    }

    public function checkNotFoundItems()
    {
        $I = $this->tester;
        $shopItemPage = new ShopItemPage($I);

        //TODO: waiForJS?
        sleep(10);
        $numberOfFoundShopItems = count($I->findElementsBy($shopItemPage->root));
        $totalNumberOfFoundItems = $I->grabIntFromString($I->by($this->foundItemsResultInfo)->text());
        $I->assertEquals(0, $numberOfFoundShopItems);
        $I->assertEquals(0, $totalNumberOfFoundItems);
    }

    public function checkMisspelMessage($misspelRequest, $trueRequest)
    {
        $I = $this->tester;

        $I->assertEquals("Запрос был исправлен. Показаны результаты по запросу «{$trueRequest}». Найти «{$misspelRequest}»", $I->by($this->infoSuggestion)->text());
    }
}
