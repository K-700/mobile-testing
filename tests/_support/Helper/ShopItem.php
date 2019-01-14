<?php

namespace Helper;

class ShopItem
{
    private $name;

    private $price;

    public $quantity;

    public function __construct($name, $price = null)
    {
        $this->setName($name);
        if (!is_null($price)) {
            $this->setPrice($price);
        }
        $this->quantity = 1;
    }

    public function setName($name)
    {
        if (empty($name)) {
            trigger_error('shop item name cant be empty', E_USER_ERROR);
        }
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        if (is_null($this->price)) {
            trigger_error("Price for '$this->name' is undefined", E_USER_ERROR);
        }
        return $this->price;
    }

    public function setPrice($price)
    {
        if ($price < 0) {
            trigger_error("Price for '$this->name' can't be negative", E_USER_ERROR);
        }
        $this->price = $price;
    }

    public function getTotalPrice()
    {
        return $this->quantity * $this->price;
    }
}