<?php

namespace Page\Checkout\CheckoutPage;

class PaymentMethod
{
    protected static $paymentSystemId = [
        '100% предоплата банковской картой' => '27',
        'Оплата при получении' => '1',
        '100% предоплата на карту Сбербанка' => '22',
        'Оплата на расчётный счёт' => '28',
    ];

    /**  @var \IosTester */
    protected $tester;

    public $root;

    public $paymentTab;

    public $discount;

    public function __construct(\IosTester $I, $paymentTypeName)
    {
        $this->tester = $I;
        // TODO: изначально планировалось сделать поиск xpath'ом по тексту $paymentTypeName, вот так:
        // $this->root = ['using' => 'xpath', 'value' => "//tr[@class='pay' and .//*[contains(text(),'$paymentTypeName')]]"];
        // но именно название платежной системы xpath найти не может, пришлось выкручиваться через массив со связью с аттрибутом for
        $paymentSystemId = self::$paymentSystemId[$paymentTypeName];
        $this->root = ['using' => 'xpath', 'value' => "//tr[@class='pay' and .//label[@for='shop_payment_system_id_$paymentSystemId']]"];
        $this->paymentTab = ['using' => 'xpath', 'value' => "//span[contains(text(),'Оплата')]"];
        $this->discount = 0;

        if ($paymentTypeName == '100% предоплата на карту Сбербанка') {
                $this->discount = 5;
        }
    }
}