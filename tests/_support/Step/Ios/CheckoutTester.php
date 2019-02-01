<?php
namespace Step\Ios;

use Helper\Cart;
use Helper\TestUser;
use Page\Ios\Checkout\CheckoutPage\CheckoutPage;
use Page\Ios\Checkout\FinishCheckoutPage;

class CheckoutTester extends \IosTester
{
    public function finishCheckout($parentPage, TestUser $user, Cart $cart)
    {
        $I = $this;

        $finishCheckoutPage = new FinishCheckoutPage($I, $cart, $parentPage);
        $finishCheckoutPage->checkDataBlock($user);
        $finishCheckoutPage->checkGoodsListBlock();
    }

    /**
     * @param string $phone Номер телефона (только цифры)
     * @return string Номер телеофна с маской
     */
    public function phoneWithMask($phone)
    {
        return '+7 ('.substr($phone, 0, 3).')'.substr($phone, 3,3).'-'.substr($phone, 6, 2).'-'.substr($phone, 8);
    }

    /**
     * @param string $error
     * @param CheckoutPage $checkoutPage
     */
    public function seeError($error, CheckoutPage $checkoutPage)
    {
        $I = $this;

        $I->see($error, $I->findBy($checkoutPage->errorBlock));
    }

    /**
     * @param CheckoutPage $checkoutPage
     */
    public function cantGoToNextStep(CheckoutPage $checkoutPage)
    {
        $I = $this;

        $I->seeElement($I->findElementFromElementBy($I->findBy($checkoutPage->root), $checkoutPage->nextStepButtonDisabled));
    }
}