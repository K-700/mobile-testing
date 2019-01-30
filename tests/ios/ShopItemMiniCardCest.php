<?php

use Page\Checkout\FullCartPage;
use Page\ShopItem\ShopItemPage;
use Codeception\Example;

class ShopItemMiniCardCest
{
    /**
     * Проверка мини-карточек товаров
     * Проверка на наличие непустого имени, цена >= 0, добавление товара в корзину
     *
     * @param IosTester $I
     * @param Example $itemType
     * @param FullCartPage $fullCartPage
     * @dataProvider itemTypeDataProvider
     */
    public function shopItemTest(IosTester $I, Example $itemType, FullCartPage $fullCartPage)
    {
        $shopItemPage = new ShopItemPage($I, $itemType);
        $shopItemPage->goToPageWithCurrentItem();

        $I->amGoingTo('add first shop item to cart');
        $currentShopItem = $shopItemPage->getFirstPresentItem();
        $I->findElementFromElementBy($currentShopItem, $shopItemPage->addToCartButton)->click();

        $fullCartPage->cartPage->addItems($currentShopItem, 1);
        $fullCartPage->cartPage->checkItems();
    }

    protected function itemTypeDataProvider()
    {
        return [
            ShopItemPage::ITEM,
            ShopItemPage::ITEM_SEARCH,
//            ShopItemPage::ITEM_RETAIL_ROCKET  TODO: раскомментить после починки прогрузки retail-rocket
        ];
    }
}

