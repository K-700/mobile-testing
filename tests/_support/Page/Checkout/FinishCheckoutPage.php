<?php

namespace Page\Checkout;

use Page\Checkout\CheckoutPage\CheckoutPage;
use Helper\TestUserHelper;
use Helper\CartHelper;
use Page\CartPage;
use Step\Ios\CheckoutTester;

class FinishCheckoutPage
{
    /** @var \IosTester */
    protected $tester;

    public $URL;

    /** @var OneClickPage|CheckoutPage $parentPage Page from which the order was placed */
    public $parentPage;

    /** @var string Root locator */
    public $root;

    public $thankData;
    public $phoneData;
    public $deliveryData;

    public $deliveryMessage;

    /** блок "Заказанные товары" */
    public $goodsListHeader;
    public $finishCart;

    public $printButton;

    public function __construct(CheckoutTester $I, CartHelper $cart, $parentPage)
    {
        $this->tester = $I;

        $this->parentPage = $parentPage;
        if ($this->parentPage instanceof CheckoutPage) {
            $this->URL = 'shop/cart/finish/';
        } elseif ($this->parentPage instanceof OneClickPage) {
            $this->URL = 'shop/cart/quickfinish/';
        }

        $this->root = ['using' => 'class name', 'value' => 'adptr-order-finish'];
        $this->thankData = ['using' => 'class name', 'value' => 'adptr-order-finish__title'];
        $this->phoneData = ['using' => 'xpath', 'value' => "//div[@class='adptr-order-finish__top']//p[@class='adptr-order-finish__text'][1]"];
        $this->deliveryData = ['using' => 'xpath', 'value' => "//div[@class='adptr-order-finish__top']//p[@class='adptr-order-finish__text'][2]"];

        /** блок "Заказанные товары" */
        $this->goodsListHeader = ['using' => 'xpath', 'value' => "//div[@class='cart-final-block']//h2"];
        $this->finishCart = new CartPage($I, CartPage::FINISH_CART, $cart);
        $this->deliveryMessage = '* без учета стоимости доставки. Стоимость доставки будет расчитана в процессе подтверждения заказа сотрудником колл-центра.';
    }

    /**
     * Проверка введенных данных на странице оформления заказа
     *
     * @param TestUserHelper $user
     */
    public function checkDataBlock(TestUserHelper $user)
    {
        $I = $this->tester;
        // TODO: проверять номер заказа
        $I->expectTo('see all entered fields');
        $I->see($user->name, $I->findBy($this->thankData));
        $I->see($I->phoneWithMask($user->phone), $I->findBy($this->phoneData));
        if ($this->parentPage instanceof CheckoutPage) {
            //TODO: В случае исправление бага с двойным городом в аресе доставки разбить стекло и расскомментировать блок
            if ($user->deliveryType) {
                $I->see($user->getFullAddress(), $I->findBy($this->deliveryData));
            }
            $I->dontSeeElementBy($this->deliveryData);
        } elseif ($this->parentPage instanceof OneClickPage) {
            $I->expectTo('see message about delivery price');
            $I->see($this->deliveryMessage, $I->findBy($this->root));
        }
    }

    public function checkGoodsListBlock()
    {
        $I = $this->tester;

        $I->assertEquals($I->findBy($this->goodsListHeader)->text(), "Заказанные товары");
        $this->finishCart->checkItems();
    }
}