<?php
namespace Exception;

use Helper\ShopItem;

class ItemIsOutOfStockException extends \Exception
{
    /** @var ShopItem */
    private $shopItem;

    public function __construct(ShopItem $shopItem)
    {
        parent::__construct("Item '{$shopItem->getName()}' is out of stock");
        $this->shopItem = $shopItem;
    }

    public function getShopItem()
    {
        return $this->shopItem;
    }
}