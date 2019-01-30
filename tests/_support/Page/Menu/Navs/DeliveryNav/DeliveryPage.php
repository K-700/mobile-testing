<?php

namespace Page\Menu\Navs\DeliveryNav;

class DeliveryPage
{
    /** @var \IosTester */
    protected $tester;

    /** @var string Root locator */
    public $root;

    public $URL;

    public $header;

    public $selectedCity;

    public $cityButtonYes;

    public $cityButtonNo;

    public $regionSelect;

    public $citySelect;

    public $noMyCityButton;

    public $chooseFromListButton;

    public $inputCityField;

    public $acceptCityButton;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->URL = '/shipping/';
        $this->root = ["using" => "class name", "value" => "text_content"];
        $this->selectedCity = ["using" => "id", "value" => "city-selected"];

        $this->cityButtonYes = ['using' => 'xpath', 'value' => "//a[@class='otvet' and contains(text(), 'Да')]"];
        $this->cityButtonNo = ['using' => 'xpath', 'value' => "//a[@class='otvet' and contains(text(), 'Нет')]"];
        $this->regionSelect = ["using" => "id", "value" => "shop_country_location_id"];
        $this->citySelect = ["using" => "id", "value" => "shop_country_location_city_id"];
        $this->noMyCityButton = ['using' => 'xpath', 'value' => "//a[contains(text(), 'Нет моего города')]"];
        $this->chooseFromListButton = ['using' => 'xpath', 'value' => "//a[contains(text(), 'Выбрать из списка')]"];
        $this->inputCityField = ["using" => "id", "value" => "user_city"];
        $this->acceptCityButton = ["using" => "id", "value" => "hideFadeWindow"];
    }

    /**
     * @param string $region
     * @param string $city
     */
    public function inputDetectableCity($region, $city)
    {
        $I = $this->tester;

        $I->findBy($this->cityButtonNo)->click();
        $I->findBy($this->regionSelect)->click();
        $I->findElementFromElementBy($I->findBy($this->regionSelect), ["using" => "xpath", "value" => "//option[contains(text(), '$region')"])->click();
        $I->pauseExecution();
//        $I->waitAllScripts();
        $I->findElementFromElementBy($I->findBy($this->citySelect), ["using" => "xpath", "value" => "//option[contains(text(), '$city')"])->click();
//        $I->selectOption($this->citySelect, $city);
        $I->findBy($this->acceptCityButton)->click();
    }

    /**
     * @param string $region
     * @param string $city
     */
//    public function inputUndetectableCity($region, $city)
//    {
//        $I = $this->tester;
//
//        $I->click($this->cityButtonNo);
//        $I->selectOption($this->regionSelect, $region);
//        $I->click($this->noMyCityButton);
//        $I->waitAllScripts();
//        $I->canSeeElement($this->chooseFromListButton);
//        $I->fillField($this->inputCityField, $city);
//        $I->click($this->acceptCityButton);
//    }
}