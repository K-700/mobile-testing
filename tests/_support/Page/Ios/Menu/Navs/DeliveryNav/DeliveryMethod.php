<?php

namespace Page\Ios\Menu\Navs\DeliveryNav;

class DeliveryMethod
{
    /** @var \IosTester */
    protected $tester;

    /** @var array Root locator */
    public $root;

    public $entity;

    public $image;

    public $name;

    public $deliveryTime;

    public $description;

    public $pickupPointsRoot;
    public $pickupPointsCity;
    public $pickupPointsList;
    public $pickupPointsOpenButton;
    public $pickupPointsCloseButton;
    public $pickupPointsPickupPoint;
    public $pickupPointsPickupPointAddress;
    public $pickupPointOpenMapButton;
    public $pickupPointCloseMapButton;
    public $pickupPointsPickupPointMap;

    public function __construct(\IosTester $I, $deliveryName)
    {
        $this->tester = $I;
        $this->entity = $deliveryName;

        switch ($this->entity) {
            case 'imkosmetik_shop':
                $this->root = ['using' => 'id', 'value' => "pay_id14"];
                break;
            case 'sdek':
                $this->root = ['using' => 'id', 'value' => "pay_id13"];
                break;
            case 'courier':
                $this->root = ['using' => 'id', 'value' => "pay_id10"];
                break;
            case 'dpd':
                $this->root = ['using' => 'id', 'value' => "pay_id11"];
                break;
            case 'mail':
                $this->root = ['using' => 'id', 'value' => "pay_id1"];
                break;
        }

        if ($this->entity == 'sdek' || $this->entity == 'dpd') {
            if ($this->entity == 'sdek') {
                $description = 'Адреса пунктов выдачи заказов СДЭК';
            } elseif ($this->entity == 'dpd') {
                $description = 'Адреса пунктов выдачи заказов DPD';
            }

            $this->pickupPointsRoot = ['using' => 'xpath', 'value' => "//div[@class='pvz-list-wrapper' and contains(text(), '$description')]"];
            $this->pickupPointsCity = ['using' => 'xpath', 'value' => "//div[@class='city-name']//div[@class='text-name']"];
            $this->pickupPointsOpenButton = ['using' => 'class name', 'value' => "show-pvz-list-link"];
            $this->pickupPointsCloseButton = ['using' => 'xpath', 'value' => "//a[@class='show-pvz-list-link open']"];
            $this->pickupPointsList = ['using' => 'class name', 'value' => "pvz-list"];
            $this->pickupPointsPickupPoint = ['using' => 'class name', 'value' => 'pvz-wrapper'];
            $this->pickupPointsPickupPointMap = ['using' => 'xpath', 'value' => "//div[@class='map-container']/ymaps"];
            $this->pickupPointsPickupPointAddress = ['using' => 'xpath', 'value' => "//div[@class='row']//div[position()=1]"];
            $this->pickupPointOpenMapButton = ['using' => 'class name', 'value' => 'open-map'];
            $this->pickupPointCloseMapButton = ['using' => 'class name', 'value' => 'open-map open'];
        }

        $this->name = ['using' => 'xpath', 'value' => "//td[@class='opinion']//p[1]"];
        $this->deliveryTime = ['using' => 'xpath', 'value' => "//td[@class='opinion']//p[2]"];
        $this->description = ['using' => 'xpath', 'value' => "//td[3]//div[1]"];
    }

    /**
     * Проверка плашек с типами доставок
     * Проверяется цена, время доставки (на непустоту), описание (на непустоту)
     */
    public function checkDeliveryContainer()
    {
        $I = $this->tester;

        $root = $I->findBy($this->root);
        $I->assertNotEmpty($I->findElementFromElementBy($root, $this->name)->text());
        $I->assertNotEmpty($I->findElementFromElementBy($root, $this->deliveryTime)->text());
        $I->assertNotEmpty($I->findElementFromElementBy($root, $this->description)->text());
    }

    /**
     * Проверка списка пунктов самовывоза
     * Проверяется адрес пунктов (на непустоту), появление яндекс-карты. Проверка осуществляется только для СДЕК и DPD
     *
     * @param string $city
     */
    public function checkPickupPoints($city)
    {
        $I = $this->tester;

        if ($this->entity != 'sdek' && $this->entity != 'dpd')
            return;

        $pickupPointsRoot = $I->findBy($this->pickupPointsRoot);
        $I->assertEquals($city, $I->findElementFromElementBy($pickupPointsRoot, $this->pickupPointsCity)->text());
        $I->verticalSwipeToElement($pickupPointsRoot);

        $I->expect('that list of pickup points is collapsed');
        $pickupPoints = $I->findElementsFromElementBy($pickupPointsRoot, $this->pickupPointsPickupPoint);
        $I->assertCount(0, count($pickupPoints));
        $I->findElementFromElementBy($pickupPointsRoot, $this->pickupPointsOpenButton)->click();
        $I->waitForElementVisible($I->findElementFromElementBy($pickupPointsRoot, $this->pickupPointsCloseButton));

        $I->expect('that list of pickup points is expanded');
        $pickupPoints = $I->findElementsFromElementBy($pickupPointsRoot, $this->pickupPointsPickupPoint);
        $I->assertGreaterOrEquals(1, count($pickupPoints));
        foreach ($pickupPoints as $pickupPoint) {
            $I->verticalSwipeToElement($pickupPoint);
            $I->assertNotEmpty($I->findElementFromElementBy($pickupPoint, $this->pickupPointsPickupPointAddress)->text());
            $I->assertFalse($I->findElementFromElementBy($pickupPoint, $this->pickupPointsPickupPointMap)->displayed());
            $I->findElementFromElementBy($pickupPoint, $this->pickupPointOpenMapButton)->click();
            $I->expectTo('see yandex map');
            $I->waitForElementVisible($I->findElementFromElementBy($pickupPoint, $this->pickupPointsPickupPointMap));
            $I->findElementFromElementBy($pickupPoint, $this->pickupPointCloseMapButton)->click();
            $I->waitForElementVisible($I->findElementFromElementBy($pickupPoint, $this->pickupPointOpenMapButton));
            $I->waitForElementNotVisible($I->findElementFromElementBy($pickupPoint, $this->pickupPointsPickupPointMap));
        }
    }
}