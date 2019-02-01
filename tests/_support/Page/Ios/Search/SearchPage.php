<?php
namespace Page\Ios\Search;

use Page\Ios\ShopItem\ShopItemPage;

class SearchPage
{
    /** @var \IosTester  */
    protected $tester;

    private $foundItemsResultInfo;

    private $infoSuggestion;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->foundItemsResultInfo = ["using" => "xpath", "value" => "//div[@class='search-page-info-result']"];
        $this->infoSuggestion = ["using" => "class name", "value" => "search-page-info-suggestion"];
    }

    public function checkFoundItems($regexp)
    {
        $I = $this->tester;
        $shopItemPage = new ShopItemPage($I, ShopItemPage::ITEM_SEARCH);

        // by() ждет появления хотя-бы одного найденного товара, тогда как findElementsBy() не делает этого
        // если убрать строку ниже, то всегда будет найдено 0 товаров, по мне, так это лучше, чем sleep()
        $I->findBy($shopItemPage->root);
        $foundShopItems = $I->findElementsBy($shopItemPage->root);
        $I->assertGreaterThanOrEqual(1, count($foundShopItems));
        $I->assertGreaterThanOrEqual(1, $I->grabIntFromString($I->findBy($this->foundItemsResultInfo)->text()));
        foreach ($foundShopItems as $shopItem) {
            $I->assertRegExp("/$regexp/ui", $I->findElementFromElementBy($shopItem, $shopItemPage->name)->text());
        }
    }

    public function checkNotFoundItems()
    {
        $I = $this->tester;
        $shopItemPage = new ShopItemPage($I, ShopItemPage::ITEM_SEARCH);

        sleep(15);
        $numberOfFoundShopItems = count($I->findElementsBy($shopItemPage->root));
        $totalNumberOfFoundItems = $I->grabIntFromString($I->findBy($this->foundItemsResultInfo)->text());
        $I->assertEquals(0, $numberOfFoundShopItems);
        $I->assertEquals(0, $totalNumberOfFoundItems);
    }

    public function checkMisspelMessage($misspelRequest, $trueRequest)
    {
        $I = $this->tester;

        $I->assertEquals("Запрос был исправлен. Показаны результаты по запросу «{$trueRequest}». Найти «{$misspelRequest}»", $I->findBy($this->infoSuggestion)->text());
    }
}
