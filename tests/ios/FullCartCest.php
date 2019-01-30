<?php

use Helper\CartHelper;
use Page\Checkout\FullCartPage;
use Page\HeaderPage;

class FullCartCest
{
    /** @var CartHelper */
    private $cart;

    public function _before(IosTester $I)
    {
        $I->setUrl(['url' => 'http://test-site.com/shop/nails/gel-laki']);
        sleep(5);
        $this->cart = $I->addRandomDifferentItemsToCart(3, 5);

        $headerPage = new HeaderPage($I);
        $headerPage->goToCart();
    }

    /**
     * Тест для корзины
     * Рандомно наполняется корзина, затем находится товар с активной кнопкой "+"
     * Для этого товара проверяются кнопки "+", "-" и "х"(удаление)
     *
     * @param IosTester $I
     * @group restartSession
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
