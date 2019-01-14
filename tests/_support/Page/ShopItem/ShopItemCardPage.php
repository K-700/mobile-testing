<?php
namespace Page\ShopItem;

use Helper\Cart;
use Helper\ShopItem;
use Page\CacklePage;

class ShopItemCardPage
{
    protected $tester;

    public $root;

    public $photo;

    public $smallPhoto;

    public $name;

    public $propertyRow;

    public $propertyName;

    public $propertyValue;

    public $shareSection;
    public $shareIcon;

    public $description;

    public $price;

    public $quantity;

    public $addToCartButton;

    public $descriptionContent;
    public $descriptionButton;

    public $addToQuantityButton;
    public $subFromQuantityButton;

    public $commentsButton;
    public $commentsCount;
    /** @var CacklePage */
    public $commentsBlock;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ["using" => "class name", "value" => "shop-item-wrapper"];
        $this->photo = ["using" => "id", "value" => "gallery"];
        $this->smallPhoto = ["using" => "class name", "value" => "small_photo_container"];
        $this->name = ["using" => "class name", "value" => "item-view-header"];
        $this->description = ["using" => "class name", "value" => "item-view-description"];

        $this->propertyRow = ["using" => "class name", "value" => "shop_property"];
        $this->propertyName = ["using" => "class name", "value" => "shop_property_name"];
        $this->propertyValue = ["using" => "class name", "value" => "shop_property_value"];

        $this->shareSection = ["using" => "class name", "value" => "b-share"];
        $this->shareIcon = ["using" => "css selector", "value" => ".{$this->shareSection['value']} .b-share-icon"];

        $this->price = ["using" => "class name", "value" => "item-view-current-price"];
        $this->quantity = ["using" => "xpath", "value" => "//input[@data-rule='quantity']"];
        $this->addToCartButton = ["using" => "class name", "value" => "put-item-button"];

        $this->descriptionContent = ["using" => "xpath", "value" => "//div[@data-tab='submenu-content-description']"];
        $this->descriptionButton = ["using" => "xpath", "value" => "//div[@data-tab-target='submenu-content-description']"];

        $this->addToQuantityButton = ["using" => "class name", "value" => "imk-icon-keyboard-arrow-up"];
        $this->subFromQuantityButton = ["using" => "class name", "value" => "imk-icon-keyboard-arrow-down"];

        $this->commentsButton = ["using" => "xpath", "value" => "//div[@data-tab='submenu-content-reviews']"];
        $this->commentsCount = ["using" => "id", "value" => "item-view-review-count"];
        $this->commentsBlock = new CacklePage($I);
    }


    /**
     * Проверка полей с описанием свойств (напр. "Артикул", "Производитель" и т.п.)
     */
    public function checkProperties()
    {
        $I = $this->tester;

        $properties = $I->findElementsBy($this->propertyRow);
        $I->assertGreaterThanOrEqual(1, count($properties));
        foreach ($properties as $property) {
            $I->assertNotEmpty($I->findElementFromElementBy($property, $this->propertyName));
            $I->assertNotEmpty($I->findElementFromElementBy($property, $this->propertyValue));
        }
    }

    public function increaseQuantityByOne()
    {
        $I = $this->tester;

        $I->by($this->addToQuantityButton)->click();
    }

    public function reduceQuantityByOne()
    {
        $I = $this->tester;

        $I->by($this->subFromQuantityButton)->click();
    }

    public function grabQuantity()
    {
        $I = $this->tester;

        return (int)$I->by($this->quantity)->text();
    }

    public function grabPrice()
    {
        $I = $this->tester;

        return (int)$I->by($this->price)->text();
    }

    /**
     * Ввод количества товара напрямую в поле количества
     *
     * @param $numberToInput
     */
    public function inputQuantity($numberToInput)
    {
        $I = $this->tester;

        $I->click($this->quantity);
        $I->by($this->quantity)->clear();
        // Стираем 1 которая уже есть в поле
//        $I->sendKeys($I->by($this->quantity), \WebDriverKeys::BACKSPACE);
        $I->by($this->quantity)->value($numberToInput);
    }

    /**
     * Ввод количества товара напрямую в поле количества и добавление этого товара в корзину
     *
     * @param int $numberToAdd Сколько товара необходимо добавить в корзину
     * @param Cart|null $cart Ссылка на карточку с товарами
     * @throws ItemIsOutOfStockException Товар закончился на складе
     */
    public function addItemsToCart($numberToAdd, Cart &$cart = null)
    {
        $I = $this->tester;

        $this->inputQuantity($numberToAdd);
        $I->click($this->addToCartButton);
        if ($I->by($this->addToCartButton)->text() == 'Сообщить о поступлении') {
            throw new ItemIsOutOfStockException(new ShopItem($I->by($this->name)->text(), $this->grabPrice()));
        }
        // добавляем товар в карточку только если он остался на складе (не было исключения)
        if (!is_null($cart)) {
            $cart->addItems(new ShopItem(new ShopItem($I->by($this->name)->text(), $this->grabPrice()), $this->grabQuantity());
        }
    }
}