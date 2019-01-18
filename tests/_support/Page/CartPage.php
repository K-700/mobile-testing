<?php
namespace Page;

use Exception\ItemIsOutOfStockException;
use Facebook\WebDriver\Exception\ElementNotVisibleException;
use Helper\Cart;
use Helper\ShopItem;

class CartPage
{
    /** Entity constants */
    const CART = 1;
//    const CHECKOUT_CART = 3;
//    const FINISH_CART = 4;

    protected $url;

    /** @var \IosTester */
    protected $tester;

    /** @var string Name of entity */
    protected $entity;

    /** @var Cart */
    public $cart;

    /** @var string Root locator */
    public $root;

    public $table;

//    public $tableHeaders;

    public $shopItemRoot;
    public $shopItemName;
    public $shopItemPrice;
    public $shopItemQuantity;
    public $shopItemTotalPrice;

    public $totalPrice;

    /** cart */
    public $addButton;
    public $subButton;
    public $deleteButton;
//    /** finish cart */
//    public $discount;

    public function __construct(\IosTester $I, $constEntity, $cart = null)
    {
        $this->tester = $I;
        $this->cart = $cart;
        $this->entity = $constEntity;

        // TODO: ВНИМАНИЕ! Если изменятся локаторы на этой странице, то нужно не забыть поменять их в функциях getShopItemAddButton и getShopItemSubButton
//        $this->shopItemRoot = ['using' => 'class name', 'value' => 'shop-cart-item-data'];
        $this->shopItemRoot = ['using' => 'xpath', 'value' => "//tr[@class='shop-cart-item-data']"];
        $this->shopItemName = ['using' => 'class name', 'value' => 'cart_table_name'];
        $this->shopItemPrice = ['using' => 'class name', 'value' => 'cart_table_price'];
        $this->shopItemQuantity = ['using' => 'class name', 'value' => 'cart_table_quantity'];
        $this->shopItemTotalPrice = ['using' => 'class name', 'value' => 'cart_table_summary'];
        $this->deleteButton = ['using' => 'class name', 'value' => 'imk-icon-close'];
        //addButton subButton должны быть в xpath для функции findShopItemNameWithActiveButton
        $this->addButton = ['using' => 'xpath', 'value' => "//span[@class='circle-button' and contains(text(), '+')]"];
        $this->subButton = ['using' => 'xpath', 'value' => "//span[@class='circle-button' and contains(text(), '-')]"];
        $this->totalPrice = ['using' => 'id', 'value' => 'order-price'];

        switch ($constEntity) {
            case self::CART:
                $this->root = ['using' => 'id', 'value' => 'carttbl'];
                $this->table = ['using' => 'class name', 'value' => 'full-cart-table'];
                $this->url = '/shop/cart/';
                break;
//            case self::CHECKOUT_CART:
//                $this->root = Locator::find('div', ['class' => 'cart-final-block']);
//                $this->table = Locator::find('table', ['class' => 'shop_cart_table all_cart_table_shop checkout-cart-table']);
//                $this->totalPrice = Locator::toXPath('#order-price');
//                break;
//            case self::FINISH_CART:
//                $this->root = Locator::find('div', ['class' => 'print-area']);
//                $this->table = $this->root . Locator::find('table', ['class' => 'shop_cart_table']);
//                $this->totalPrice = Locator::elementAt(Locator::contains('td', 'Итого') . '/ancestor::tr//td', 3);
//                $this->discount = Locator::elementAt(Locator::contains('td', 'скидка') . '/ancestor::tr//td', 3);
//                break;
        }
    }

    public function grabShopItemName($shopItem)
    {
        $I = $this->tester;

        $shopItemName = $I->findElementFromElementBy($shopItem, $this->shopItemName);
        $I->verticalSwipeToElement($shopItemName);
        return $shopItemName->text();
    }

    public function grabShopItemPrice(\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem)
    {
        $I = $this->tester;

        $shopItemPrice = $I->findElementFromElementBy($shopItem, $this->shopItemPrice);
        $I->verticalSwipeToElement($shopItemPrice);
        if ($I->grabIntFromString($shopItemPrice->text() == 1)) {
            codecept_debug($shopItemPrice->text());
            $I->pauseExecution();
        }
        return $I->grabIntFromString($shopItemPrice->text());
    }

    public function grabShopItemQuantity(\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem)
    {
        $I = $this->tester;

        return $I->grabIntFromString($I->findElementFromElementBy($shopItem, $this->shopItemQuantity)->text());
    }

    public function grabShopItemTotalPrice(\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem)
    {
        $I = $this->tester;

        return $I->grabIntFromString($I->findElementFromElementBy($shopItem, $this->shopItemTotalPrice)->text());
    }

    public function grabTotalPrice()
    {
        $I = $this->tester;

        return $I->grabIntFromString($I->by($this->totalPrice)->text());
    }

    /**
     * Возвращает кнопку '+' для переданного товара
     *
     * Зачем это надо, если можно сделать
     *      $I->findElementFromElementBy($shopItem, $this->addButton);
     * ?. Пробовала, это не прокатывает (скорее всего, из-за того, что товары присутствуют в DOM в 2х экземплярах
     * Но Appium пофиг, что мы ищем кнопку через XPath от конкретного элемента, который уже найден,
     * а не от корня DOM), так что пришлось выкручиваться. Причем, если задать кнопку не XPath а CSS, то он ее
     * найдет (что доказывается кнопкой удаления товара), но через CSS нельзя делать выборку по контенту, что необходимо в нашем случае
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getShopItemAddButton($shopItem)
    {
        $I = $this->tester;

        $name = $this->grabShopItemName($shopItem);
        return $I->by(["using" => "xpath", "value" => "//*[@id='carttbl']//a[contains(text(), '$name')]/ancestor::tr[@class='shop-cart-item-data']{$this->addButton['value']}"]);
    }

    /**
     * Возвращает кнопку '-' для переданного товара
     *
     * Зачем это надо, если можно сделать
     *      $I->findElementFromElementBy($shopItem, $this->subButton);
     * ?. Пробовала, это не прокатывает (скорее всего, из-за того, что товары присутствуют в DOM в 2х экземплярах
     * Но Appium пофиг, что мы ищем кнопку через XPath от конкретного элемента, который уже найден,
     * а не от корня DOM), так что пришлось выкручиваться. Причем, если задать кнопку не XPath а CSS, то он ее
     * найдет (что доказывается кнопкой удаления товара), но через CSS нельзя делать выборку по контенту, что необходимо в нашем случае
     *
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getShopItemSubButton($shopItem)
    {
        $I = $this->tester;

        $name = $this->grabShopItemName($shopItem);
        return $I->by(["using" => "xpath", "value" => "//*[@id='carttbl']//a[contains(text(), '$name')]/ancestor::tr[@class='shop-cart-item-data']{$this->subButton['value']}"]);
    }

    /**
     * Finds first item in cart with active "+" button
     *
     * @param CartPage->addButton|CartPage->subButton $button
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element found item
     */
    public function findShopItemWithActiveAddButton()
    {
        $I = $this->tester;

        $shopItems = $I->findElementsFromElementBy($I->by($this->root), $this->shopItemRoot);
        foreach ($shopItems as $shopItem) {
            if ($this->getShopItemAddButton($shopItem)->displayed()) {
                return $shopItem;
            }
        }
        $I->skip("Can't find element with active '+' button");
    }

    /**
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @param int $numberToAdd number of items to add
     */
    public function addItems($shopItem, $numberToAdd)
    {
        $I = $this->tester;

        try {
            $I->amGoingTo("add $numberToAdd item(s)");
            for ($i = 0; $i < $numberToAdd; $i++) {
                $addButton = $this->getShopItemAddButton($shopItem);
                if ($addButton->displayed()) {
                    $addButton->click();
                } else {
                    throw new ItemIsOutOfStockException(new ShopItem($this->grabShopItemName($shopItem), $this->grabShopItemPrice($shopItem)));
                }
            }
        } catch (ItemIsOutOfStockException $e) {
            // мы не знаем, тест сфейлился потому что товар кончился, или потому что кнопка отвалилась, поэтому напишем обе ошибки. Для проверки можно будет запустить тест еще пару раз
            $I->incomplete("Element with strategy '{$this->addButton['using']}' and value '{$this->addButton['value']} is not visible or not exists' or " . ($e->getMessage()));
        }

        $this->cart->addItems(new ShopItem($this->grabShopItemName($shopItem)), $numberToAdd);
    }

    /**
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @param int $numberToSub number of items to sub
     */
    public function subItems($shopItem, $numberToSub)
    {
        $I = $this->tester;

        try {
            $I->amGoingTo("sub $numberToSub item(s)");
            for ($i = 0; $i < $numberToSub; $i++) {
                $subButton = $this->getShopItemSubButton($shopItem);
                if ($subButton->displayed()) {
                    $subButton->click();
                } else {
                    throw new ElementNotVisibleException("Element with strategy '{$this->subButton['using']}' and value '{$this->subButton['value']} is not visible or not exists'");
                }
            }
        } catch (ElementNotVisibleException $e) {
            $I->fail($e->getMessage());
        }

        $this->cart->subItems(new ShopItem($this->grabShopItemName($shopItem)), $numberToSub);
    }

    /**
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     */
    public function deleteItem($shopItem)
    {
        $I = $this->tester;

        $I->amGoingTo('delete item');
        $this->cart->deleteItem(new ShopItem($this->grabShopItemName($shopItem)));
        $I->findElementFromElementBy($shopItem, $this->deleteButton)->click();
    }

    public function checkItems()
    {
        $I = $this->tester;
        $headerPage = new HeaderPage($I);

        if ($this->entity == self::CART && $I->getRelativeUrl() != $this->url) {
            // если находимся фиг знает где, то перейдем в корзину
            $I->by($headerPage->basketButton)->click();
        }

        $I->comment('Check all items');
        foreach ($I->findElementsBy($this->shopItemRoot) as $shopItemOnPage)
        {
            $I->verticalSwipeToElement($shopItemOnPage);
            $I->expectTo("see item from page in cart");
            $shopItem = $this->cart->shopItems[$this->grabShopItemName($shopItemOnPage)];

            $I->amGoingTo("compare prices");
            $I->assertEquals($shopItem->getPrice(), $this->grabShopItemPrice($shopItemOnPage));

            $I->amGoingTo("compare quantities");
            $I->assertEquals($shopItem->quantity, $this->grabShopItemQuantity($shopItemOnPage));

            $I->amGoingTo("compare total prices");
            $I->assertEquals($shopItem->getTotalPrice(), $this->grabShopItemTotalPrice($shopItemOnPage));
        }

//        $totalPrice = $this->cart->getTotalPrice();
//        if ($this->entity == self::FINISH_CART && $this->cart->discount > 0) {
//            // проверка скидки и всей цены со скидкой
//            $I->expectTo('see discount');
//            $I->assertEquals($I->grabIntFrom($this->discount), $this->cart->discount);
//        }
        $I->amGoingTo('check total price');
        //TODO: На товар приходится погрешность ~2 руб на позицию. Когда будет исправлена проблема с округлением, это нужно будет исправить
        $I->assertEqualsWithPermissibleLimitsOfErrors($this->grabTotalPrice(), $this->cart->getTotalPrice(), count($this->cart->shopItems) * 2);
    }
}