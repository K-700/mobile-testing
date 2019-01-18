<?php
namespace Page\ShopItem;

use Helper\Cart;
use Helper\ShopItem;
use Page\ItemIsOutOfStockPage;

class ShopItemPage
{
    protected $tester;

    /** Entity constants */
    const ITEM = 1;
    const ITEM_SEARCH = 2;

    public $root;

    public $photo;

    public $name;

    public $description;

    public $price;

    public $addToCartButton;

    public $outOfStockMessage;

    public function __construct(\IosTester $I, $constEntity = self::ITEM)
    {
        $this->tester = $I;
        $this->outOfStockMessage = 'Сообщить о поступлении';

        switch ($constEntity) {
            case self::ITEM_SEARCH:
                $this->photo = ["using" => "xpath", "value" => "//div[@class='image-block']//img"];
                $this->name = ["using" => "class name", "value" => "item-title"];
                $this->description = ["using" => "xpath", "value" => "//div[@class='description-block']//span"];
                $this->price = ["using" => "xpath", "value" => "//div[@class='price-block']"];
                $this->addToCartButton = ["using" => "xpath", "value" => "//div[@class='add-to-cart-button']//a"];
                $this->root = ["using" => "class name", "value" => "shop_item"];
                break;
            case self::ITEM:
                $this->photo = ["using" => "xpath", "value" => "//a[@class='mphoto']/img"];
                $this->name = ["using" => "class name", "value" => "item_link"];
                $this->description = ["using" => "class name", "value" => "dsc"];
                $this->price = ["using" => "class name", "value" => "price"];
                $this->addToCartButton = ["using" => "class name", "value" => "put-item-button"];
                $this->root = ["using" => "class name", "value" => "shop_item"];
                break;
        }
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return string Shop item name
     */
    public function grabName($shopItem)
    {
        $I = $this->tester;

        return $I->findElementFromElementBy($shopItem, $this->name)->text();
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return int Shop item price
     */
    public function grabPrice($shopItem)
    {
        $I = $this->tester;

        return (int)$I->findElementFromElementBy($shopItem, $this->price)->text();
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return string Shop item description
     */
    public function grabDescription($shopItem)
    {
        $I = $this->tester;

        return $I->findElementFromElementBy($shopItem, $this->description)->text();
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @param Cart $cart
     * @return bool Item added successfully
     */
    public function addToCart($shopItem, &$cart)
    {
        $I = $this->tester;
        $addButton = $I->findElementFromElementBy($shopItem, $this->addToCartButton);
        $I->verticalSwipeToElement($addButton);
        //TODO: пошаманить с этой хуетой
        $I->tap([[$addButton->location()['x'] + 40, $addButton->location()['y'] + 130]]);
//        $addButton->click();
        if ($addButton->text() == $this->outOfStockMessage) {
            $I->comment("Item is out of stock");
            $itemIsOutOfStockPage = new ItemIsOutOfStockPage($I);
            $itemIsOutOfStockPage->closeWindow();
            return false;
        }
        $cart->addItems(new ShopItem($this->grabName($shopItem), $this->grabPrice($shopItem)));

        return true;
    }
}
