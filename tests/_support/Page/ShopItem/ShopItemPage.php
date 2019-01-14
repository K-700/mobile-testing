<?php
namespace Page\ShopItem;

class ShopItemPage
{
    protected $tester;

    public $root;

    public $photo;

    public $name;

    public $description;

    public $price;

    public $addToCartButton;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ["using" => "class name", "value" => "shop_item"];
        $this->photo = ["using" => "xpath", "value" => "//div[class='image-block']//img"];
        $this->name = ["using" => "class name", "value" => "item-title"];
        $this->description = ["using" => "xpath", "value" => "//div[class='description-block']//span"];
        $this->price = ["using" => "xpath", "value" => "//div[class='price-block']"];
        $this->addToCartButton = ["using" => "xpath", "value" => "//div[class='add-to-cart-button']//a"];
    }
}
