<?php

use Codeception\Example;
use Helper\CartHelper;
use Helper\TestUserHelper;
use Page\Checkout\CheckoutPage\CheckoutPage;
use Page\Checkout\FullCartPage;
use Page\Checkout\OneClickPage;
use Page\HeaderPage;
use Step\Ios\CheckoutTester;

class CheckoutCest
{
    /** @var CartHelper */
    private $cart;

    public function _before(IosTester $I)
    {
        $I->amOnPage('/shop/nails/gel-laki');
        $I->waitUrlChange($I->getUrl());
        $this->cart = $I->addRandomDifferentItemsToCart(2, 1);

        // переход в корзину
        $headerPage = new HeaderPage($I);
        $headerPage->goToCart();
    }

    /**
     * Тест для заказа в 1 клик
     * Проверяется список товаров на странице корзины. На странице окончания оформления заказа проверяется введенная информация,
     * список товаров.
     *
     * @param CheckoutTester $I
     * @param OneClickPage $oneClickPage
     * @param Example $userData
     * @dataProvider userDataProvider
     * @group restartSession
     * @throws Exception
     */
//    public function oneClickCheckout(CheckoutTester $I, OneClickPage $oneClickPage, Example $userData)
//    {
//        $user = new TestUserHelper($userData);
//
//        $I->by($oneClickPage->oneClickButton)->click();
//        $oneClickPage->submitForm($user);
//        $I->finishCheckout($oneClickPage, $user, $this->cart);
//    }

    /**
     * Тест для оформления заказа
     * Рандомно наполняется корзина, с помощью userDataProvider вводятся данные доставки,
     * контактная информация, способ оплаты. Проверяется список товаров на странице корзины.
     * На странице оформления проверяется корзина с товарами, итоговая сумма. На странице окончания
     * оформления заказа проверяется введенная информация, список товаров.
     *
     * @param CheckoutTester $I
     * @param Example $userData
     * @dataProvider userDataProvider
     * @throws Exception
     */
    public function checkout(CheckoutTester $I, Example $userData)
    {
        $user = new TestUserHelper($userData);
        $fullCartPage =  new FullCartPage($I, $this->cart);
        $checkoutPage = new CheckoutPage($I, $this->cart);

        $I->findBy($fullCartPage->goToCheckoutButton)->click();
        $checkoutPage->inputValidContactInfo($user);
        $checkoutPage->goToNextStep();
        $checkoutPage->inputDetectableDeliveryCity($user->city, $user->region);
        $checkoutPage->checkAdditionalInfoBlock();
        $user->setFullAddress($checkoutPage->inputDelivery($user));
        $checkoutPage->goToNextStep();
        $checkoutPage->inputPaymentType($user, $this->cart);
        // добавим доставку в корзину
        $checkoutPage->addDeliveryToCart($this->cart);
        codecept_debug('before check coupon');
        $I->pauseExecution();
        $checkoutPage->checkCouponBlock();
        $checkoutPage->checkout();
        $I->finishCheckout($checkoutPage, $user, $this->cart);
    }

    /**
     * Тест для оформления заказа с неопределяющимся городом на странице оформления заказа
     * Аналогичен тесту checkout, за исключением иного способа ввода города
     *
     * @param CheckoutTester $I
     * @param Example $userData
     * @group restartSession
     * @dataProvider userDataUndetectableCityProvider
     * @throws Exception
     */
//    public function checkoutWithUndetectableDeliveryCity(CheckoutTester $I, Example $userData)
//    {
//        $user = new TestUserHelper($userData);
//        $fullCartPage =  new FullCartPage($I, $this->cart);
//        $checkoutPage = new CheckoutPage($I, $this->cart);
//
//        $I->by($fullCartPage->goToCheckoutButton)->click();
//        $checkoutPage->inputValidContactInfo($user);
//        $checkoutPage->inputUndetectableDeliveryCity($user->city, $user->region);
//        $user->setFullAddress($checkoutPage->inputDelivery($user));
//        $checkoutPage->inputPaymentType($user, $this->cart);
//        $checkoutPage->addDeliveryToCart($this->cart);
//        $I->click($checkoutPage->checkoutButton);
//        $I->finishCheckout($checkoutPage, $user, $this->cart);
//    }

    protected function userDataProvider()
    {
        return [
            [
                'name' => 'Владимир Владимирович Краб',
                'phone' => '3254839210',
                'mail' => 'krab@mail.ru',
                'delivery_type' => 'Курьер',
                'payment_type' => '100% предоплата банковской картой',
                'country' => 'Россия',
                'region' => 'Башкортостан (Республика)',
                'city' => 'Уфа',
                'street' => 'Пушкина',
                'building' => '30',
                'building_add' => 'C',
                'flat' => '18'
            ],
//            [
//                'name' => 'Шмеле Дмитрий Анатольевич',
//                'phone' => '3254839210',
//                'mail' => 'shmele@imkosmetik.com',
//                'delivery_type' => 'Почтой',
//                'payment_type' => '100% предоплата на карту Сбербанка',
//                'country' => 'Россия',
//                'region' => 'Пензенская область',
//                'city' => 'Верхняя Елюзань',
//                'street' => 'Пушкина',
//                'building' => '30',
//                'building_add' => 'C',
//                'flat' => '18',
//                'postcode' => '454128'
//            ],
//            [
//                'name' => 'Челябинский челик',
//                'phone' => '3254839210',
//                'mail' => 'chelik@imkosmetik.com',
//                'delivery_type' => 'Самовывоз DPD',
//                'payment_type' => 'Оплата при получении',
//                'country' => 'Россия',
//                'region' => 'Челябинская область',
//                'city' => 'Челябинск'
//            ],
//            [
//                'name' => 'Челябинский челик2',
//                'phone' => '3254839210',
//                'mail' => 'chelik@imkosmetik.com',
//                'delivery_type' => 'В фирменный магазин Имкосметик',
//                'payment_type' => 'Оплата на расчётный счёт',
//                'country' => 'Россия',
//                'region' => 'Челябинская область',
//                'city' => 'Челябинск'
//            ],
//            [
//                'name' => 'Московский мажор',
//                'phone' => '3254839210',
//                'mail' => 'major@imkosmetik.com',
//                'delivery_type' => 'Самовывоз СДЕК',
//                'payment_type' => 'Оплата при получении',
//                'country' => 'Россия',
//                'region' => 'Москва и Московская область',
//                'city' => 'Москва'
//            ]
        ];
    }

    protected function userDataUndetectableCityProvider()
    {
        return [
            [
                'name' => 'Деревенская баба',
                'phone' => '3254839210',
                'mail' => 'baba@imkosmetik.com',
                'delivery_type' => 'Почтой',
                'payment_type' => 'Оплата при получении',
                'country' => 'Россия',
                'region' => 'Тамбовская область',
                'city' => 'Кукуево',
                'street' => 'Пушкина',
                'building' => '30',
                'building_add' => 'C',
                'flat' => '18',
                'postcode' => '454128'
            ]
        ];
    }
}
