<?php
namespace Page\Checkout\CheckoutPage;

use Helper\CartHelper;
use Helper\ShopItemHelper;
use Page\CartPage;
use Helper\TestUserHelper;
use Step\Ios\CheckoutTester;

class CheckoutPage
{
    /** @var CheckoutTester */
    protected $tester;

    public $URL;

    /** @var string Root locator */
    public $root;

    // активная кнопка "продолжить" на странице оформления
    public $nextStepButton;
    // неактивная кнопка "продолжить" на странице оформления
    public $nextStepButtonDisabled;

    /** блок "Контактная информация" */
    public $contactInfoTabButton;
    public $contactInfoBlock;
    public $contactInfoNameField;
    public $contactInfoPhoneField;
    public $contactInfoMailField;
    public $contactInfoSendSmsCheckbox;
    public $contactInfoSendSmsMessage;
    public $contactInfoEmailMailingCheckbox;
    public $contactInfoEmailMailingMessage;

    /** блок доставки */
    public $deliveryBlock;
    /** подблок "Адрес доставки" */
    public $deliveryAddressChangeButton;
    public $deliveryAddressCityName;
    public $deliveryAddressCityInput;
    public $deliveryAddressContinueButton;
    public $deliveryAddressContinueMessage;
    public $deliveryAddressProposedCities;
    public $deliveryAddressProposedCity;
    public $deliveryAddressManualInputButton;
    public $deliveryAddressRegionSelect;
    /** подблок "Способы доставки" */
    /** @var DeliveryMethod */
    public $deliveryMethod;

    /** блок "Дополнительная информация" */
    public $additionalInfoNeedCertificatesCheckbox;
    public $additionalInfoNeedCertificatesLabel;
    public $additionalInfoNeedCashMemoCheckbox;
    public $additionalInfoNeedCashMemoLabel;
    public $commentForOrder;

    /** блок "Выбор формы оплаты" */
    /** @var PaymentMethod */
    public $paymentType;

    /** блок "Купон" */
    public $couponShowButton;
    public $couponCloseButton;
    public $couponBlock;
    public $couponInputField;
    public $couponActivateButton;
    public $couponResetButton;
    public $couponError;

    /** блок "Список товаров" */
    /** @var CartPage */
    public $cartPage;

    /** Итого */
    public $deliveryPrice;
    public $fullPrice;
    public $discount;
    public $checkoutButton;

    public $errorBlock;

    public function __construct(CheckoutTester $I, CartHelper $cart)

    {
        $this->tester = $I;
        $this->cartPage = new CartPage($I, CartPage::CHECKOUT_CART, $cart);

        $this->URL = '/shop/cart/checkout/';
        $this->root = ['using' => 'class name', 'value' => 'checkout'];
        $this->nextStepButton = ['using' => 'class name', 'value' => "adptr-order-btn"];
        $this->nextStepButtonDisabled = ['using' => 'class name', 'value' => "adptr-order-btn_disabled"];

        /** блок "Контактная информация" */
        $this->contactInfoTabButton = ['using' => 'xpath', 'value' => "//span[contains(text(),'Покупатель')]"];
        $this->contactInfoBlock = ['using' => 'id', 'value' => 'contacts'];
        $this->contactInfoSendSmsCheckbox = ['using' => 'id', 'value' => 'send-sms-info'];
        $this->contactInfoSendSmsMessage = ['using' => 'xpath', 'value' => "//label[@for='send-sms-info']"];
        $this->contactInfoEmailMailingCheckbox = ['using' => 'id', 'value' => 'send-email-info'];
        $this->contactInfoEmailMailingMessage = ['using' => 'xpath', 'value' => "//label[@for='send-email-info']"];
        $this->contactInfoNameField = ['using' => 'id', 'value' => 'surname-input'];
        $this->contactInfoPhoneField = ['using' => 'id', 'value' => 'phone-input'];
        $this->contactInfoMailField = ['using' => 'id', 'value' => 'email-input'];

        /** блок доставки */
        $this->deliveryBlock = ['using' => 'class name', 'value' => 'delivery-block'];
        /** подблок "Адрес доставки" */
        $this->deliveryAddressContinueButton = ['using' => 'id', 'value' => 'apply-change-city'];
        $this->deliveryAddressContinueMessage = ['using' => 'class name', 'value' => 'location-save-hint'];
        $this->deliveryAddressChangeButton = ['using' => 'id', 'value' => 'change-city'];
        $this->deliveryAddressCityName = ['using' => 'id', 'value' => 'city-name'];
        $this->deliveryAddressCityInput = ['using' => 'id', 'value' => 'city-input'];
        $this->deliveryAddressProposedCities = ['using' => 'xpath', 'value' => "//ul[@class='ui-autocomplete ui-menu ui-widget ui-widget-content ui-corner-all']"];
        $this->deliveryAddressProposedCity = ['using' => 'class name', 'value' => 'ui-menu-item'];
        $this->deliveryAddressManualInputButton = ['using' => 'xpath', 'value' => "//li[@class='ui-menu-item' and a[contains(text(), 'Ввести вручную')]]"];
        $this->deliveryAddressRegionSelect = ['using' => 'id', 'value' => "location-select"];

        /** блок "Дополнительная информация" */
        $this->additionalInfoNeedCertificatesCheckbox = ['using' => 'id', 'value' => 'need_certificates'];
        $this->additionalInfoNeedCertificatesLabel = ['using' => 'xpath', 'value' => "//label[@for='need_certificates']"];
        $this->additionalInfoNeedCashMemoCheckbox = ['using' => 'id', 'value' => 'need_cash_memo'];
        $this->additionalInfoNeedCashMemoLabel = ['using' => 'xpath', 'value' => "//label[@for='need_cash_memo']"];
        $this->commentForOrder = ['using' => 'xpath', 'value' => "//textarea[@name='description']"];

        /** блок "Купон" */
        $this->couponShowButton = ['using' => 'xpath', 'value' => "//a[@id='show-coupon-button' and contains(text(),'Есть купон?')]"];
        $this->couponCloseButton = ['using' => 'xpath', 'value' => "//a[@id='show-coupon-button' and contains(text(),'Свернуть')]"];
        $this->couponBlock = ['using' => 'id', 'value' => 'coupon-block'];
        $this->couponInputField = ['using' => 'id', 'value' => 'coupon-code'];
        $this->couponActivateButton = ['using' => 'id', 'value' => 'check-coupon'];
        $this->couponResetButton = ['using' => 'id', 'value' => 'reset-coupon'];
        $this->couponError = ['using' => 'id', 'value' => 'coupon-error'];

        /** Итого */
        $this->deliveryPrice = ['using' => 'xpath', 'value'  => "//tr[.//td[contains(text(), 'Доставка')]]"];
        $this->fullPrice = ['using' => 'xpath', 'value'  => "//tr[.//td[contains(text(), 'Итого')]]"];
        $this->discount =  ['using' => 'xpath', 'value'  => "//tr[.//td[contains(text(), 'Скидка')]]"];
        $this->checkoutButton = ['using' => 'id', 'value' => 'submit'];

        $this->errorBlock = ['using' => 'id', 'value' => 'validate-errors'];
    }

    public function goToNextStep()
    {
        $I = $this->tester;
        $nextStepButton = $I->findVisibleElementFromElementBy($I->findBy($this->root), $this->nextStepButton);

        $I->verticalSwipeToElement($nextStepButton);
        $nextStepButton->click();
    }

    public function checkout()
    {
        $I = $this->tester;
        $checkoutButton =$I->findBy($this->checkoutButton);

        $I->verticalSwipeToElement($checkoutButton);
        $checkoutButton->click();
    }

    /**
     * @return int Delivery price
     */
    protected function grabDeliveryPrice()
    {
        $I = $this->tester;

        return $I->grabIntFromString($I->findBy($this->deliveryPrice)->text());
    }

    /**
     * @return int Discount
     */
    protected function grabDiscount()
    {
        $I = $this->tester;

        return $I->grabIntFromString($I->findBy($this->discount)->text());
    }

    /**
     * @return int Full price (items's price with delivery and discount)
     */
    protected function grabFullPrice()
    {
        $I = $this->tester;

        return $I->grabIntFromString($I->findBy($this->fullPrice)->text());
    }

    /**
     * Ввод личных данных покупателя
     *
     * @param TestUserHelper $user
     */
    public function inputValidContactInfo(TestUserHelper $user)
    {
        $I = $this->tester;

        $I->amGoingTo('input user\'s contacts');
        $root = $I->findBy($this->contactInfoBlock);
        $I->findElementFromElementBy($root, $this->contactInfoTabButton)->click();
        $I->findElementFromElementBy($root, $this->contactInfoNameField)->value($user->name);
        $I->cantGoToNextStep($this);
        $I->findElementFromElementBy($root, $this->contactInfoPhoneField)->value($user->phone);
        $I->cantGoToNextStep($this);

        $mailField = $I->findElementFromElementBy($root, $this->contactInfoMailField);
        $I->verticalSwipeToElement($mailField);
        $mailField->value($user->mail);

        $I->assertTrue($I->findBy($this->contactInfoSendSmsCheckbox)->selected());
        $I->assertNotEmpty($I->findBy($this->contactInfoSendSmsMessage)->text());
        // TODO: не появляется чекбокс для мыла
        // $I->assertTrue($I->by($this->contactInfoEmailMailingCheckbox)->selected());
        // $I->assertNotEmpty($I->by($this->contactInfoEmailMailingMessage)->text());
    }

    /**
     * Ввод определяющегося города
     *
     * @param string $city
     * @param string $region
     */
    public function inputDetectableDeliveryCity($city, $region)
    {
        $I = $this->tester;

        $I->amGoingTo('check detectable city input');
        $I->dontSeeElementBy($this->deliveryAddressCityInput);
        $I->findBy($this->deliveryAddressChangeButton)->click();
        $I->findBy($this->deliveryAddressCityInput)->value($city);
        $I->cantGoToNextStep($this);
        sleep(5);

        $I->expect("that will be more than 1 proposed fields");
        $proposedCities = $I->findElementsFromElementBy($I->findBy($this->deliveryAddressProposedCities), $this->deliveryAddressProposedCity);
        $I->assertGreaterOrEquals(2, count($proposedCities));
        $I->see($city, $proposedCities[0]);
        $I->see($region, $proposedCities[0]);
        $I->verticalSwipeToElement($proposedCities[0]);
        // подводим элемент к верху страницы чтобы он верно кликнулся
//        $I->swipe(0, 400, 0, 100);
        $proposedCities[0]->click();

        $I->dontSeeElementBy($this->deliveryAddressCityInput);
        $I->see($city, $I->findBy($this->deliveryAddressCityName));
    }

    /**
     * Ввод неопределяющегося города
     *
     * @param string $city
     * @param string $region
     * @throws \NoSuchElementException
     */
    public function inputUndetectableDeliveryCity($city, $region)
    {
        $I = $this->tester;

        $I->amGoingTo('check manual city input');
        $I->dontSeeElementBy($this->deliveryAddressCityInput);
        $I->findBy($this->deliveryAddressChangeButton)->click();
        $I->findBy($this->deliveryAddressCityInput)->value($city);
        sleep(5);

        $I->expect("that will be only 1 proposed field: \"{$this->deliveryAddressManualInputButton}\"");
        $proposedCities = $I->findElementsFromElementBy($I->findBy($this->deliveryAddressProposedCities), $this->deliveryAddressProposedCity);
        $I->assertEquals(count($proposedCities), 1);
        $I->findBy($this->deliveryAddressManualInputButton)->click();

        $I->selectOption($I->findBy($this->deliveryAddressRegionSelect), $region);
        $I->assertEquals("Нажмите \"Продолжить\"", $I->findBy($this->deliveryAddressContinueMessage)->text());
        $I->click($this->deliveryAddressContinueButton, $this->deliveryBlock);
        $I->see($city, $I->findBy($this->deliveryAddressCityName));
    }

    /**
     * Ввод способа доставки
     *
     * @param TestUserHelper $user
     * @return string|null
     * @throws \Exception
     */
    public function inputDelivery(TestUserHelper $user)
    {
        $I = $this->tester;

        $this->deliveryMethod = new DeliveryMethod($I, $user->deliveryType);
        $I->cantGoToNextStep($this);
        $deliverAddress = $this->deliveryMethod->inputDelivery($user);
        $this->checkTotalBlock($this->deliveryMethod->grabPrice());

        return $deliverAddress;
    }

    /**
     * Add delivery to ShopItems[] in cart
     *
     * @param CartHelper $cart
     */
    public function addDeliveryToCart(CartHelper &$cart)
    {
        $cart->addItems(new ShopItemHelper($this->deliveryMethod->name, $this->grabDeliveryPrice()));
    }

    /**
     * @param TestUserHelper $user
     * @param CartHelper|null $cart
     * @throws \Exception
     */
    public function inputPaymentType(TestUserHelper $user, CartHelper &$cart = null)
    {
        $I = $this->tester;

        $this->paymentType = new PaymentMethod($I, $user->paymentType);

        $I->expectTo('see error message');
        $this->checkout();
        $I->seeError('Вы не выбрали тип платежа', $this);

        $I->findBy($this->paymentType->root)->click();
        codecept_debug('click payment type');
        $I->pauseExecution();
        $this->checkTotalBlock(null, $this->paymentType->discount);
        if (!is_null($cart)) {
            // установим скидку для всех товаров в корзине (если скидки не было, то ничего не изменится)
            $cart->setDiscount($this->paymentType->discount);
        }
    }

    /**
     * Check block with prices and items. If parameter is null, then this function doesn't check that price
     *
     * @param int|null $deliveryPrice
     * @param int|null $discountInPercents
     * @throws \Exception
     */
    public function checkTotalBlock($deliveryPrice = null, $discountInPercents = null)
    {
        $I = $this->tester;
        $orderPrice = $this->cartPage->cart->getTotalPrice();

        $this->cartPage->checkItems();
        if (!is_null($deliveryPrice)) {
            $I->assertEquals($deliveryPrice, $this->grabDeliveryPrice());
        } else {
            // т.к. стоимость доставки всегда показывается на странице, то можем взять оттуда стоимость без проверки
            $deliveryPrice = $this->grabDeliveryPrice();
        }

        if (!is_null($discountInPercents) && $discountInPercents > 0) {
            $discount = ceil($discountInPercents * $orderPrice / 100);
            $I->assertEqualsWithPermissibleLimitsOfErrors($discount, $this->grabDiscount(), 1);
        } else {
            // а вот скидку по предоплате показывает не всегда, так что просто взять мы ее уже не можем
            $discount = 0;
        }

        $fullPrice = $orderPrice + $deliveryPrice - $discount;
        $I->assertEqualsWithPermissibleLimitsOfErrors($fullPrice, $this->grabFullPrice(), 1);
    }

    /**
     * Проверка блока с сертефикатом, чеком и комментариями
     */
    public function checkAdditionalInfoBlock()
    {
        $I = $this->tester;

        $I->verticalSwipeToElement($I->findBy($this->additionalInfoNeedCertificatesLabel));
        $I->see('Нужны сертификаты', $I->findBy($this->additionalInfoNeedCertificatesLabel));
        $I->findBy($this->additionalInfoNeedCertificatesLabel)->click();
        sleep(3);
        $I->seeCheckboxIsChecked($I->findBy($this->additionalInfoNeedCertificatesCheckbox));

        $I->verticalSwipeToElement($I->findBy($this->additionalInfoNeedCashMemoLabel));
        $I->see('Нужен товарный чек', $I->findBy($this->additionalInfoNeedCashMemoLabel));
        $I->findBy($this->additionalInfoNeedCashMemoLabel)->click();
        sleep(3);
        $I->pauseExecution();
        $I->seeCheckboxIsChecked($I->findBy($this->additionalInfoNeedCashMemoCheckbox));

        $someText = 'Бла бла бла';
        $I->findBy($this->commentForOrder)->value($someText);
        $I->assertEquals($someText, $I->findBy($this->commentForOrder)->value());
    }

    /**
     * Проверка блока с купонами
     *
     * @throws \Exception
     */
    public function checkCouponBlock()
    {
        $I = $this->tester;

        $I->amGoingTo('activate coupon with incorrect number');
        $I->assertFalse($I->findBy($this->couponBlock)->displayed());
        $I->findBy($this->couponShowButton)->click();
        $I->dontSeeElementBy($this->couponError);
        $I->findBy($this->couponInputField)->value(1111);
        $I->findBy($this->couponActivateButton)->click();
        $I->assertEquals('Ошибка при активации купона', $I->findBy($this->couponError)->text());
        $I->findBy($this->couponResetButton)->click();
        $I->dontSeeElementBy($this->couponError);
        $I->findBy($this->couponCloseButton)->click();
        $I->dontSeeElementBy($this->couponBlock);
    }
}