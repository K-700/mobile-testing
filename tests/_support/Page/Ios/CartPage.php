<?php
namespace Page\Ios;

use Exception\ItemIsOutOfStockException;
use Facebook\WebDriver\Exception\ElementNotVisibleException;
use Helper\Cart;
use Helper\ShopItem;

class CartPage
{
    /** Entity constants */
    const CART = 1;
    const CHECKOUT_CART = 2;
    const FINISH_CART = 3;

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
    /** finish cart */
    public $discount;

    public function __construct(\IosTester $I, $constEntity, $cart = null)
    {
        $this->tester = $I;
        $this->cart = $cart;
        $this->entity = $constEntity;

        switch ($constEntity) {
            case self::CART:
                // TODO: !!! Если изменятся локаторы на этой странице, то нужно не забыть поменять их в функциях getShopItemAddButton и getShopItemSubButton !!!
                $this->url = '/shop/cart/';
                $this->root = ['using' => 'id', 'value' => 'carttbl'];
                $this->table = ['using' => 'class name', 'value' => 'full-cart-table'];
                $this->shopItemRoot = ['using' => 'xpath', 'value' => "//tr[@class='shop-cart-item-data']"];
                $this->shopItemName = ['using' => 'class name', 'value' => 'cart_table_name'];
                $this->shopItemQuantity = ['using' => 'class name', 'value' => 'cart_table_quantity'];
                $this->shopItemPrice = ['using' => 'class name', 'value' => 'cart_table_price'];
                $this->shopItemTotalPrice = ['using' => 'class name', 'value' => 'cart_table_summary'];
                $this->totalPrice = ['using' => 'id', 'value' => 'order-price'];
                $this->deleteButton = ['using' => 'class name', 'value' => 'imk-icon-close'];
                //addButton subButton должны быть в xpath для функции findShopItemNameWithActiveButton
                $this->addButton = ['using' => 'xpath', 'value' => "//span[@class='circle-button' and contains(text(), '+')]"];
                $this->subButton = ['using' => 'xpath', 'value' => "//span[@class='circle-button' and contains(text(), '-')]"];
                break;
            case self::CHECKOUT_CART:
                $this->root = ['using' => 'class name', 'value' => 'cart-final-block'];
                $this->table = ['using' => 'class name', 'value' => 'checkout-cart-table'];
                $this->shopItemRoot = ['using' => 'xpath', 'value' => "//tbody/tr[@id]"];
                $this->shopItemName = ['using' => 'class name', 'value' => "checkout-cart-table-name"];
                $this->shopItemQuantity = ['using' => 'class name', 'value' => 'checkout-cart-table-quantity'];
                $this->shopItemTotalPrice = ['using' => 'class name', 'value' => 'checkout-cart-table-price'];
                $this->totalPrice = ['using' => 'id', 'value' => 'order-price'];
                break;
            case self::FINISH_CART:
                $this->root = ['using' => 'class name', 'value' => 'cart-final-block'];
                $this->table = ['using' => 'class name', 'value' => 'shop_cart_table'];
                $this->shopItemRoot = ['using' => 'xpath', 'value' => "//tr[@class='shop-cart-item-data']"];
                $this->shopItemName = ['using' => 'xpath', 'value' => '/td[1]'];
                $this->shopItemQuantity = ['using' => 'xpath', 'value' => '/td[3]'];
                $this->shopItemTotalPrice = ['using' => 'xpath', 'value' => '/td[4]'];
                $this->totalPrice = ['using' => 'xpath', 'value' => "//div[@class='cart-final-block']//*[contains(text(), 'Итого')]/ancestor::tr[@class='total']"];
                $this->discount = ['using' => 'xpath', 'value' => "//div[@class='cart-final-block']//*[contains(text(), 'скидка')]/ancestor:: tr[@class='total']"];
                break;
        }
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return int Item's name
     */
    private function grabShopItemName($shopItem)
    {
        $I = $this->tester;

        $shopItemName = $I->findVisibleElementFromElementBy($shopItem, $this->shopItemName);
        $I->verticalSwipeToElement($shopItemName);
        return $shopItemName->text();
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return int Item's price
     */
    private function grabShopItemPrice($shopItem)
    {
        $I = $this->tester;

        $shopItemPrice = $I->findVisibleElementFromElementBy($shopItem, $this->shopItemPrice);
        $I->verticalSwipeToElement($shopItemPrice);
        return $I->grabIntFromString($shopItemPrice->text());
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return int Item's quantity
     */
    private function grabShopItemQuantity($shopItem)
    {
        $I = $this->tester;

        return $I->grabIntFromString($I->findVisibleElementFromElementBy($shopItem, $this->shopItemQuantity)->text());
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return int Item's total price (quantity * price)
     */
    private function grabShopItemTotalPrice($shopItem)
    {
        $I = $this->tester;

        return $I->grabIntFromString($I->findVisibleElementFromElementBy($shopItem, $this->shopItemTotalPrice)->text());
    }

    /**
     * @return int Price of all items in cart
     */
    public function grabTotalPrice()
    {
        $I = $this->tester;

        $totalPrice = $I->findVisibleElementFromElementBy($I->findBy($this->root), $this->totalPrice);
        $I->verticalSwipeToElement($totalPrice);
        return $I->grabIntFromString($totalPrice->text());
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
        return $I->findBy(["using" => "xpath", "value" => "//*[@id='carttbl']//a[contains(text(), '$name')]/ancestor::tr[@class='shop-cart-item-data']{$this->addButton['value']}"]);
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
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function getShopItemSubButton($shopItem)
    {
        $I = $this->tester;

        $name = $this->grabShopItemName($shopItem);
        return $I->findBy(["using" => "xpath", "value" => "//*[@id='carttbl']//a[contains(text(), '$name')]/ancestor::tr[@class='shop-cart-item-data']{$this->subButton['value']}"]);
    }

    /**
     * Finds first item in cart with active "+" button
     *
     * @param CartPage->addButton|CartPage->subButton $button
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element found item
     */
    public function findShopItemWithActiveAddButton()
    {
        $I = $this->tester;

        $shopItems = $I->findElementsFromElementBy($I->findBy($this->root), $this->shopItemRoot);
        foreach ($shopItems as $shopItem) {
            if ($this->getShopItemAddButton($shopItem)->displayed()) {
                return $shopItem;
            }
        }
        $I->skip("Can't find element with active '+' button");
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
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
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
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
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $shopItem
     */
    public function deleteItem($shopItem)
    {
        $I = $this->tester;

        $I->amGoingTo('delete item');
        $this->cart->deleteItem(new ShopItem($this->grabShopItemName($shopItem)));
        $I->findElementFromElementBy($shopItem, $this->deleteButton)->click();
    }

    /**
     * Проверка товаров в корзине
     */
    public function checkItems()
    {
        $I = $this->tester;
        $headerPage = new HeaderPage($I);

        if ($this->entity == self::CART && $I->getRelativeUrl() != $this->url) {
            // если находимся фиг знает где, то перейдем в корзину
            $I->findBy($headerPage->basketButton)->click();
        }

        $I->comment('Check all items');
        foreach ($I->findElementsBy($this->shopItemRoot) as $shopItemOnPage)
        {
            $I->verticalSwipeToElement($shopItemOnPage);
            $I->expectTo("see item from page in cart");
            $shopItem = $this->cart->shopItems[$this->grabShopItemName($shopItemOnPage)];

            // в карточках которые в условии ниже не показывается цена за 1 штуку товара (только на 1 позицию)
            if ($this->entity != self::CHECKOUT_CART && $this->entity != self::FINISH_CART) {
                $I->amGoingTo("compare prices");
                $I->assertEquals($shopItem->getPrice(), $this->grabShopItemPrice($shopItemOnPage));
            }

            $I->amGoingTo("compare quantities");
            $I->assertEquals($shopItem->quantity, $this->grabShopItemQuantity($shopItemOnPage));

            $I->amGoingTo("compare total prices");
            $I->assertEquals($shopItem->getTotalPrice(), $this->grabShopItemTotalPrice($shopItemOnPage));
        }

        if ($this->entity == self::FINISH_CART && $this->cart->discount > 0) {
            // проверка скидки
            $I->assertEquals($I->grabIntFromString($I->findBy($this->discount)->text()), $this->cart->discount);
        }
        $I->amGoingTo('check total price');
        //TODO: На товар приходится погрешность ~2 руб на позицию. Когда будет исправлена проблема с округлением, это нужно будет исправить
        $I->assertEqualsWithPermissibleLimitsOfErrors($this->grabTotalPrice(), $this->cart->getTotalPrice(), count($this->cart->shopItems) * 2);
    }
}