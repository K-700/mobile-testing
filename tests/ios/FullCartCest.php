<?php

use Helper\Cart;
use Page\Checkout\FullCartPage;
use Page\HeaderPage;

class FullCartCest
{
    /** @var Cart */
    private $cart;

    public function _before(IosTester $I)
    {
        $I->implicitWait(['ms' => 10000]);
        $I->setUrl(['url' => 'http://test-site.com/shop/nails/gel-laki']);
        $elem = $I->byCssSelector('.mobile-show');
        $I->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);
        sleep(5);
        $this->cart = $I->addRandomDifferentItemsToCart(3, 5);
        // переход в корзину
        $headerPage = new HeaderPage($I);
        $oldUrl = $I->getRelativeUrl();
        $I->by($headerPage->basketButton)->click();
        // ждем пока изменится url (значит страница прогрузилась)
        $I->waitForElementChange(
            function () use ($I, $oldUrl) {
                return $I->getRelativeUrl() == $oldUrl;
            },
        20
        );

    }

    /**
     * Тест для корзины
     * Рандомно наполняется корзина, затем находится товар с активной кнопкой "+"
     * Для этого товара проверяются кнопки "+", "-" и "х"(удаление)
     *
     * @param IosTester $I
     */
    public function cartTest(\IosTester $I)
    {
        $fullCartPage = new FullCartPage($I, $this->cart);
        // TODO: предполагаем, что в корзине будет хоть 1 элемент, которого на складе 2шт, иначе тест скипнется.
        $fullCartPage->cartPage->checkItems();

        $I->amGoingTo("find item with active \"+\" button, add 1 to this item, then sub 1 and delete this item");
        $shopItem = $fullCartPage->cartPage->findShopItemWithActiveAddButton();

        $fullCartPage->cartPage->addItems($shopItem, 2);
        $fullCartPage->cartPage->checkItems();

        $fullCartPage->cartPage->subItems($shopItem, 2);
        $fullCartPage->cartPage->checkItems();

        $fullCartPage->cartPage->deleteItem($shopItem);
        $fullCartPage->cartPage->checkItems();
    }
}
