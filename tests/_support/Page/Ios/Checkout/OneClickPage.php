<?php

namespace Page\Ios\Checkout;

use Helper\TestUser;
use Step\Ios\CheckoutTester;

class OneClickPage
{
    /** @var CheckoutTester */
    protected $tester;

    /** @var string Root locator */
    public $root;

    public $surnameField;

    public $phoneField;

    public $checkoutButton;

    public $errorMessage;

    public $fields;

    public $oneClickButton;

    public function __construct(CheckoutTester $I)
    {
        $this->tester = $I;

        $this->oneClickButton = ["using" => "xpath", "value" => "//a[contains(text(),'Купить в один клик')]"];
        $this->checkoutButton = ["using" => "xpath", "value" => "//input[contains(@value,'Оформить заказ')]"];
        $this->errorMessage = 'Необходимо заполнить все поля';
        $this->root = ["using" => "class name", "value" => "mfp-content"];
        $this->surnameField = ["using" => "id", "value" => "surname-input"];
        $this->phoneField = ["using" => "id", "value" => "phone-input"];
    }

    public function submitForm(TestUser $user)
    {
        $I = $this->tester;

        $I->amGoingTo('submit one click form');
        $I->findBy($this->phoneField)->value($user->phone);

        $I->amGoingTo('submit form with empty value');
        $I->findBy($this->checkoutButton)->click();

        $I->expectTo('see error message');
        sleep(5);
        $I->see($this->errorMessage, $I->findBy($this->root));

        $I->findBy($this->surnameField)->value($user->name);
        $I->findBy($this->checkoutButton)->click();
    }
}