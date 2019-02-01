<?php

namespace Helper;

class Cart
{
    /** @var ShopItem[] */
    public $shopItems;

    public $discount;

    public function __construct()
    {
        $this->shopItems = [];
        $this->discount = 0;
    }

    public function addItems(ShopItem $shopItem, $quantity = 1)
    {
        if (!array_key_exists($shopItem->getName(), $this->shopItems)) {
            $this->shopItems[$shopItem->getName()] = $shopItem;
            $this->shopItems[$shopItem->getName()]->quantity = $quantity;
        } else {
            $this->shopItems[$shopItem->getName()]->quantity += $quantity;
        }
    }

    public function subItems(ShopItem $shopItem, $quantity = 1)
    {
        if ($this->shopItems[$shopItem->getName()]->quantity <= $quantity) {
            unset($this->shopItems[$shopItem->getName()]);
        } else {
            $this->shopItems[$shopItem->getName()]->quantity -= $quantity;
        }
    }

    public function deleteItem(ShopItem $shopItem)
    {
        unset($this->shopItems[$shopItem->getName()]);
    }

    public function getTotalPrice()
    {
        $totalPrice = 0;
        foreach ($this->shopItems as $shopItem) {
            $totalPrice += $shopItem->getTotalPrice();
        }

        return $totalPrice;
    }

    public function getTotalItemsQuantity()
    {
        $totalQuantity = 0;
        foreach ($this->shopItems as $shopItem) {
            $totalQuantity += $shopItem->quantity;
        }

        return $totalQuantity;
    }

    public function setDiscount($discountInPercenets)
    {
        $this->discount = ceil($this->getTotalPrice() * $discountInPercenets / 100);
        foreach ($this->shopItems as $shopItem) {
            $shopItem->setPrice($shopItem->getPrice() - ceil($discountInPercenets * $shopItem->getPrice() / 100));
        }
    }

    public function getByName($name)
    {
        return $this->shopItems[$name];
    }
}