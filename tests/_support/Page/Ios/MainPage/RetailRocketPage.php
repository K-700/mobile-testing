<?php
namespace Page\Ios\MainPage;

use Page\Ios\ShopItem\ShopItemPage;

class RetailRocketPage
{
    /** @var \IosTester */
    protected $tester;

    /** @var array Root locator */
    protected $root;

    protected $tab;

    public $header;

    public $items;

    /** @var ShopItemPage */
    public $item;

    public function __construct(\IosTester $I, $widgetTitle)
    {
        $this->tester = $I;

        $this->root = ['using' => 'xpath', 'value' => "//div[contains(@class,'retailrocket-widget') and .//span[contains(text(),'$widgetTitle')]"];
        $this->tab = ['using' => 'xpath', 'value' => "//div[contains(@class='rrw-tabs__tab') and .//span[contains(text(),'$widgetTitle')]"];
        $this->items = ['using' => 'class name', 'value' => 'items'];
        $this->item = new ShopItemPage($I, ShopItemPage::ITEM_RETAIL_ROCKET);
    }

    public function getWidgetItems()
    {
        $I = $this->tester;

        $tab = $I->findBy($this->tab);
        $I->verticalSwipeToElement($tab);
        $tab->click();
        $items = $I->findElementFromElementBy($I->findBy($this->root), $this->items);

        return $I->findElementsFromElementBy($items, $this->item->root);
    }

    /**
     * Провоцирует показ блока 'Вас заинтересуют'
     * Заходит на страницу рандомного гель-лака и возвращается на главную
     */
    public function triggerRetailRocket()
    {
        $I = $this->tester;

        $I->amOnPage('/shop/nails/gel-laki');
        $I->openRandomProductCard();
        $I->amOnPage('');
    }
}