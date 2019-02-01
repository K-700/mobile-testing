<?php

namespace Page\Ios\Checkout\CheckoutPage;

use Helper\TestUser;

class DeliveryMethod
{
    protected static $entities = [
        'Курьер' => 'courier',
        'Почтой' => 'mail',
        'Самовывоз DPD' => 'dpd',
        'В фирменный магазин Имкосметик' => 'imk_shop',
        'Самовывоз СДЕК' => 'sdek'
    ];

    /** @var \IosTester */
    protected $tester;
    protected $entity;

    public $root;

    public $deliveryTab;

    public $name;

    public $addressInput;

    public $select;

    public $selectOption;

    public function __construct(\IosTester $I, $deliveryTypeName)
    {
        $this->tester = $I;
        $this->name = $deliveryTypeName;
        $this->entity = self::$entities[$deliveryTypeName];

        $this->root = ['using' => 'xpath', 'value' => "//div[contains(@class,'delivery__tabs--item') and div[contains(text(),'$deliveryTypeName')]]"];
        $this->deliveryTab = ['using' => 'xpath', 'value' => "//span[contains(text(),'Доставка')]"];
        $this->addressInput = ['using' => 'xpath', 'value' => "//input[contains(@name, 'address')]"];
        $this->select = ['using' => 'xpath', 'value' => "//div[@class='adptr-order-delivery-block']//div[@class='adptr-custom-select__input']"];
        $this->selectOption = ['using' => 'xpath', 'value' => "//div[contains(@class,'adptr-custom-select__item')]"];
    }

    /**
     * Выбор адреса доставки для доставок с выбором адрес (напр. СДЕК)
     *
     * @return string Address of pickup point
     */
    private function inputDeliveryWithPickupPoints()
    {
        $I = $this->tester;

        $I->verticalSwipeToElement($I->findVisibleBy($this->select));
        $I->findVisibleBy($this->select)->click();
        $options = $I->findElementsFromElementBy($I->findBy($this->select), $this->selectOption);
        $I->expect('that there is at least one option on the page');
        $I->assertGreaterThanOrEqual(1, count($options));

        $I->amGoingTo('choose delivery point');
        $address = $options[rand(1,count($options))-1];

        return $address->text();
    }

    /**
     * Выбор адреса доставки для доставок с вводом адреса (напр. Почта)
     *
     * @param TestUser $user
     */
    private function inputDeliveryWithFillableField(TestUser $user)
    {
        $I = $this->tester;

        $I->findBy($this->addressInput)->value($user->getPartialAddress());
    }

    /**
     * @return int Delivery price
     */
    public function grabPrice()
    {
        $I = $this->tester;

        $priceAndTimeArray = explode(',', $I->findBy($this->root)->text());
        $price = end($priceAndTimeArray);
        return $I->grabIntFromString($price);
    }

    /**
     * @param TestUser $user
     * @return string|null
     */
    public function inputDelivery(TestUser $user)
    {
        $I = $this->tester;
        $cityStreetBuilding = null;
        $I->verticalSwipeToElement($I->findBy($this->root));
        $I->findBy($this->root)->click();

        switch ($this->entity) {
            case 'imk_shop':
            case 'sdek':
            case 'dpd':
                $cityStreetBuilding = $this->inputDeliveryWithPickupPoints();
                break;
            case 'courier':
            case 'mail':
                $this->inputDeliveryWithFillableField($user);
                break;
        }

        return $cityStreetBuilding;
    }
}