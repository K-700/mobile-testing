<?php

namespace Helper;

class TestUserHelper
{
    public $name;

    public $phone;

    public $mail;

    public $deliveryType;

    public $paymentType;

    public $country;

    public $region;

    public $city;

    public $street;

    public $building;

    public $buildingAdd;

    public $flat;

    public $postcode;

    /** @var string Full user's address (including postocde, country, city, etc...) for FinishCheckoutPage */
    protected $fullAddress;

    public function __construct($testUserDataArray)
    {
        // обязательные для заполнения поля
        $this->name = $testUserDataArray['name'];

        // необязательные для заполнения поля
        $this->phone = isset($testUserDataArray['phone']) ? $testUserDataArray['phone'] : null;
        $this->mail = isset($testUserDataArray['mail']) ? $testUserDataArray['mail'] : null;
        $this->deliveryType = isset($testUserDataArray['delivery_type']) ? $testUserDataArray['delivery_type'] : null;
        $this->paymentType = isset($testUserDataArray['payment_type']) ? $testUserDataArray['payment_type'] : null;
        $this->country = isset($testUserDataArray['country']) ? $testUserDataArray['country'] : null;
        $this->region = isset($testUserDataArray['region']) ? $testUserDataArray['region'] : null;
        $this->city = isset($testUserDataArray['city']) ? $testUserDataArray['city'] : null;
        $this->street = isset($testUserDataArray['street']) ? $testUserDataArray['street'] : null;
        $this->building = isset($testUserDataArray['building']) ? $testUserDataArray['building'] : null;
        $this->buildingAdd = isset($testUserDataArray['building_add']) ? $testUserDataArray['building_add'] : null;
        $this->flat = isset($testUserDataArray['flat']) ? $testUserDataArray['flat'] : null;
        $this->postcode = isset($testUserDataArray['postcode']) ? $testUserDataArray['postcode'] : null;
    }


    /**
     * Устанавливает $fullAddress. Если $cityStreetBuilding != null то заменяет часть с городом, улицей и т.д. на переданную в параметре
     *
     * @param string|null $cityStreetBuilding
     */
    public function setFullAddress($cityStreetBuilding = null)
    {
        $this->fullAddress = '';

        if ($this->deliveryType == 'Почтой') {
            $this->fullAddress .= $this->postcode ? "{$this->postcode}, " : '';
        }
        $this->fullAddress .= $this->country ? "{$this->country}" : 'Россия';
        $this->fullAddress .= $this->region ? ", {$this->region}" : '';
        if ($cityStreetBuilding) {
            $this->fullAddress .= $cityStreetBuilding;
        } else {
            $this->fullAddress .= $this->city ? ", г. {$this->city} " : '';
            $this->fullAddress .= $this->street ? ", ул. {$this->street}" : '';
            $this->fullAddress .= $this->building ? ", д. {$this->building}" : '';
            $this->fullAddress .= $this->buildingAdd ? " {$this->buildingAdd}" : '';
            $this->fullAddress .= $this->flat ? ", кв.{$this->flat}" : '';
        }
    }

    /**
     * Возвращает полный адрес
     *
     * @return string
     */
    public function getFullAddress()
    {
        if (!isset($this->fullAddress)) {
            $this->setFullAddress();
        }

        return $this->fullAddress;
    }

    /**
     * Возвращает часть адреса (начиная с улицы)
     *
     * @return string
     */
    public function getPartialAddress()
    {
        $partialAddress = '';

        $partialAddress .= $this->street ? "ул. {$this->street}" : '';
        $partialAddress .= $this->building ? ", д. {$this->building}" : '';
        $partialAddress .= $this->buildingAdd ? " {$this->buildingAdd}" : '';
        $partialAddress .= $this->flat ? ", кв.{$this->flat}" : '';

        return $partialAddress;
    }
}