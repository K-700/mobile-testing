
<?php
namespace Page;

use Helper\Cart;
use Helper\ShopItem;

class CartPage
{
    /** Entity constants */
    const CART = 1;
//    const LITTLE_CART = 2;
//    const CHECKOUT_CART = 3;
//    const FINISH_CART = 4;

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
    public $totalQuantityCircle;
//    /** finish cart */
//    public $discount;

    public function __construct(\IosTester $I, $constEntity, $cart = null)
    {
        $this->tester = $I;
        $this->cart = $cart;
        $this->entity = $constEntity;

        $this->shopItemRoot = ['using' => 'class name', 'value' => 'shop-cart-item-data'];
        $this->shopItemName = ['using' => 'class name', 'value' => 'cart_table_name'];
        $this->shopItemPrice = ['using' => 'class name', 'value' => 'cart_table_price'];
        $this->shopItemQuantity = ['using' => 'class name', 'value' => 'cart_table_quantity'];
        $this->shopItemTotalPrice = ['using' => 'class name', 'value' => 'cart_table_summary'];
        $this->deleteButton = ['using' => 'class name', 'value' => 'imk-icon-close'];
        $this->addButton = ['using' => 'xpath', 'value' => "//span[@class='circle-button' and text()[contains(.,'+')]]"];
        $this->subButton = ['using' => 'xpath', 'value' => "//span[@class='circle-button' and text()[contains(.,'-')]]"];
        $this->totalQuantityCircle = ['using' => 'class name', 'value' => 'quantity-badge'];
        $this->totalPrice = ['using' => 'id', 'value' => 'order-price'];
//        $this->tableHeaders = [
//            'photo' => [
//                'name' => 'Фото',
//                'column' => 1
//            ],
//            'name' => [
//                'name' => 'Название',
//                'column' => 2
//            ],
//            'price' => [
//                'name' => 'Цена',
//                'column' => 3
//            ],
//            'quantity' => [
//                'name' => 'Кол-во',
//                'column' => 4
//            ],
//            'total price' => [
//                'name' => 'Сумма',
//                'column' => 5
//            ],
//            'delete' => [
//                'name' => 'Удалить',
//                'column' => 6
//            ],
//        ];
//
        switch ($constEntity) {
            case self::CART:
                $this->root = ['using' => 'id', 'value' => '#carttbl'];
                $this->table = ['using' => 'full-cart-table'];
                break;
//            case self::CHECKOUT_CART:
//                $this->root = Locator::find('div', ['class' => 'cart-final-block']);
//                $this->table = Locator::find('table', ['class' => 'shop_cart_table all_cart_table_shop checkout-cart-table']);
//                $this->totalPrice = Locator::toXPath('#order-price');
//                break;
//            case self::LITTLE_CART:
//                $this->root = Locator::toXPath('#little-cart');
//                $this->table = $this->root . Locator::find('table', ['class' => 'shop_cart_table']);
//                $this->totalPrice = Locator::toXPath('.alltotal_price_number');
//                $this->totalQuantityCircle = '.quantity-badge';
//                $this->goToCartButton = 'Перейти в корзину';
//                $this->height = 34;
//                break;
//            case self::FINISH_CART:
//                $this->root = Locator::find('div', ['class' => 'print-area']);
//                $this->table = $this->root . Locator::find('table', ['class' => 'shop_cart_table']);
//                $this->totalPrice = Locator::elementAt(Locator::contains('td', 'Итого') . '/ancestor::tr//td', 3);
//                $this->discount = Locator::elementAt(Locator::contains('td', 'скидка') . '/ancestor::tr//td', 3);
//                $this->tableHeaders = [
//                    'name' => [
//                        'name' => 'Наименование',
//                        'column' => 1
//                    ],
//                    'vendor id' => [
//                        'name' => 'Артикул',
//                        'column' => 2
//                    ],
//                    'quantity' => [
//                        'name' => 'Количество',
//                        'column' => 3
//                    ],
//                    'price' => [
//                        'name' => 'Цена',
//                        'column' => 4
//                    ],
//                    'total price' => [
//                        'name' => 'Сумма',
//                        'column' => 5
//                    ]
//                ];
//
//                break;
        }
    }

    /**
     * Finds first item in cart with active "+" or "-" button
     *
     * @param CartPage->addButton|CartPage->subButton $button
     * @return string Name of found item
     */
    public function findShopItemNameWithActiveButton($button)
    {
        $I = $this->tester;

        return $I->by($this->table . $button . '/ancestor::tr' . $this->shopItemName);
    }

    /**
     * @param string $shopItemName
     * @param int $numberToAdd number of items to add
     */
    public function addItems($shopItemName, $numberToAdd)
    {
        $I = $this->tester;
        $shopItemAddButton = $this->table . Locator::contains('tr', $shopItemName) . $this->addButton;

        $I->amGoingTo("add $numberToAdd item(s)");
        $this->cart->addItems(new ShopItem($shopItemName), $numberToAdd);
        for ($i = 0; $i < $numberToAdd; $i++) {
            $I->click($shopItemAddButton);
            $I->waitAllScripts();
        }
    }

    /**
     * @param string $shopItemName
     * @param int $numberToSub number of items to sub
     */
    public function subItems($shopItemName, $numberToSub)
    {
        $I = $this->tester;
        $shopItemSubButton = $this->table . Locator::contains('tr', $shopItemName) . $this->subButton;

        $I->amGoingTo("sub $numberToSub item(s)");
        $this->cart->subItems(new ShopItem($shopItemName), $numberToSub);
        for ($i = 0; $i < $numberToSub; $i++) {
            $I->click($shopItemSubButton);
            $I->waitAllScripts();
        }
    }

    /**
     * @param string $shopItemName
     */
    public function deleteItem($shopItemName)
    {
        $I = $this->tester;
        $shopItemDeleteButton = $this->table . Locator::contains('tr', $shopItemName) . $this->deleteButton;

        $I->amGoingTo('delete item');
        $this->cart->deleteItem(new ShopItem($shopItemName));
        $I->click($shopItemDeleteButton);
        $I->waitAllScripts();
        $I->dontSee($shopItemName, $this->table);
    }

    public function getItemQuantity($shopItemName)
    {
        $I = $this->tester;

        $I->moveMouseOver($this->root);
        return $I->grabFloatFrom($this->root . Locator::contains('tr', $shopItemName) . $this->shopItemQuantity);
    }

    public function checkItems()
    {
        $I = $this->tester;
        if ($this->entity == self::LITTLE_CART) {
            $I->moveMouseOver($this->root);
        }

        $I->comment('Check table headers');
        foreach ($this->tableHeaders as $goodsTableHeader) {
            $I->canSee($goodsTableHeader['name'], Locator::elementAt($this->table . "//th", $goodsTableHeader['column']));
        }

        $I->comment('Check all items');
        foreach ($this->cart->shopItems as $shopItem) {
            $I->see($shopItem->getName(), $this->root);
            //tr где находится выбранный item для дальнейшей обработки
            $shopItemTr =  "$this->root//tr[.//*[contains(text(), '{$shopItem->getName()}')]]";
            $shopItemPrice = $I->grabIntFrom(Locator::elementAt("$shopItemTr/td", $this->tableHeaders['price']['column']));
            $shopItemQuantity = $I->grabFloatFrom(Locator::elementAt("$shopItemTr/td", $this->tableHeaders['quantity']['column']));
            $shopItemTotalPrice = $I->grabIntFrom(Locator::elementAt("$shopItemTr/td", $this->tableHeaders['total price']['column']));

            $I->amGoingTo('check price of added item in cart');
            $I->assertEqualsWithPermissibleLimitsOfErrors($shopItem->getPrice(), $shopItemPrice, 1);
            $I->amGoingTo('check quantity of added item in cart');
            $I->assertEquals($shopItem->quantity, $shopItemQuantity);
            $I->amGoingTo('check total price of added item in cart');
            $itemTotalPrice = $shopItem->getTotalPrice();
            $I->assertEqualsWithPermissibleLimitsOfErrors($itemTotalPrice, $shopItemTotalPrice, 2);
        }

        $totalPrice = $this->cart->getTotalPrice();
        if ($this->entity == self::FINISH_CART && $this->cart->discount > 0) {
            // проверка скидки и всей цены со скидкой
            $I->expectTo('see discount');
            $I->assertEquals($I->grabIntFrom($this->discount), $this->cart->discount);
        }
        $I->amGoingTo('check total price');
        //TODO: На товар приходится погрешность ~2 руб на позицию. Когда будет исправлена проблема с округлением, это нужно будет исправить
        $I->assertEqualsWithPermissibleLimitsOfErrors($I->grabIntFrom($this->totalPrice), $totalPrice, count($this->cart->shopItems) * 2);
    }
}