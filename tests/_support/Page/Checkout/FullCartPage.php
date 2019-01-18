<?php

namespace Page\Checkout;

use Helper\Cart;
use Page\CartPage;

class FullCartPage
{
    /** @var \IosTester */
    protected $tester;

    /** @var CartPage */
    public $cartPage;

    public $URL;

    public $goToCheckoutButton;

    public function __construct(\IosTester $I, Cart $cart)
    {
        $this->tester = $I;

        $this->URL = '/shop/cart/';
        $this->cartPage = new CartPage($I, CartPage::CART, $cart);
        $this->goToCheckoutButton = ["using" => "class name", "value" => "to-checkout-button"];
    }
}