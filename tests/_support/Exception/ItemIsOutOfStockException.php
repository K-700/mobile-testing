<?php
namespace Exception;

use Helper\ShopItemHelper;

class ItemIsOutOfStockException extends \Exception
{
    /** @var ShopItemHelper */
    private $shopItem;

    public function __construct(ShopItemHelper $shopItem)
    {
        parent::__construct("Item '{$shopItem->getName()}' is out of stock");
        $this->shopItem = $shopItem;
    }

    public function getShopItem()
    {
        return $this->shopItem;
    }
}