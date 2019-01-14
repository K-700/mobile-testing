<?php

use Page\CartPage;
use Helper\Cart;
use Page\Checkout\FullCartPage;

// TODO: этот тест практически полная копипаста теста LittleCartCest, за исключением того, что тестируется FullCartPage. Не знаю как их лучше объединить, поэтому пока так
class FullCartCest
{
    /** @var Cart */
    private $cart;

    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/shop/nails/gel-laki/');
        $this->cart = $I->addRandomDifferentItemsToCart(3, 5);
        $littleCart = new CartPage($I, CartPage::LITTLE_CART, $this->cart);
        $I->moveMouseOver($littleCart->root);
        $I->click($littleCart->goToCartButton, $littleCart->root);
    }

    /**
     * Тест для корзины
     * Рандомно наполняется корзина, затем находится товар с активной кнопкой "+"
     * Для этого товара проверяются кнопки "+", "-" и "х"(удаление)
     *
     * @param AcceptanceTester $I
     */
    public function cartTest(AcceptanceTester $I)
    {
        $fullCartPage = new FullCartPage($I, $this->cart);
        // TODO: предполагаем, что в корзине будет хоть 1 элемент, которого на складе 2шт, иначе тест сфейлится. Есть вариант через try-catch (обернуть findShopItemNameWithActiveButton), но выглядит так себе.
        $fullCartPage->cartPage->checkItems();

        $I->amGoingTo("find item with active \"+\" button, add 1 to this item, then sub 1 and delete this item");
        $shopItemName = $fullCartPage->cartPage->findShopItemNameWithActiveButton($fullCartPage->cartPage->addButton);

        $fullCartPage->cartPage->addItems($shopItemName, 1);
        $fullCartPage->cartPage->checkItems();

        $fullCartPage->cartPage->subItems($shopItemName, 1);
        $fullCartPage->cartPage->checkItems();

        $fullCartPage->cartPage->deleteItem($shopItemName);
        $fullCartPage->cartPage->checkItems();
    }
}
