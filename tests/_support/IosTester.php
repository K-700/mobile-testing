<?php

use Codeception\Exception\ElementNotFound;
use Facebook\WebDriver\Exception\TimeOutException;
use Helper\CartHelper;
use Page\HeaderPage;
use Page\ShopItem\ShopItemPage;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class IosTester extends \Codeception\Actor
{
    use _generated\IosTesterActions;

    /**
     * @param int $min
     * @param int $max
     * @param int $quantity Amount of returned numbers
     * @return array Array with random and unique numbers within given range
     */
    protected static function uniqueRandomNumbersWithinRange($min, $max, $quantity)
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    /**
     * Calls the function provided until the Closure return value is not false.
     *
     * @param Closure $closure
     * @param int $timeoutInSeconds
     * @param int $intervalInMilliseconds
     * @param string $message
     *
     * @throws TimeOutException
     * @throws Exception
     */
    protected function until(Closure $closure, $message = '', $timeoutInSeconds = 30, $intervalInMilliseconds = 250)
    {
        $end = microtime(true) + $timeoutInSeconds;
        $last_exception = null;

        while ($end > microtime(true)) {
            try {
                if (!$closure()) {
                    return;
                }
            } catch (Exception $e) {
                $last_exception = $e;
            }
            usleep($intervalInMilliseconds * 1000);
        }

        if ($last_exception) {
            throw $last_exception;
        }

        throw new TimeOutException($message);
    }

    /**
     * @param array $data
     * @options {"required":["using","value"]}
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function findVisibleBy($data)
    {
        return $this->findElementsBy($data)[0];
    }

    /**
     * Returns displayed elements
     *
     * @param array $data
     * @options {"required":["using","value"]}
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function findElementsBy($data)
    {
        $elements = $this->findElements($data);

        $elements = array_filter($elements, function($webElement) {
            return $this->elementDisplayed($webElement['ELEMENT']);
        });

        //array_values для перестроения ключей
        return array_values(array_map(function($webElement) {
            return $this->elementFromResponseValue($webElement);
        }, $elements));
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $parentElement
     * @param $childData
     * @options {"required":["using","value"]}
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function findElementFromElementBy($parentElement, $childData)
    {
        return $this->elementFromResponseValue($this->findElementFromElement($childData, $parentElement->toWebDriverObject()['ELEMENT']));
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $parentElement
     * @param $childData
     * @options {"required":["using","value"]}
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function findVisibleElementFromElementBy($parentElement, $childData)
    {
        return $this->findElementsFromElementBy($parentElement, $childData)[0];
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $parentElement
     * @param $childData
     * @options {"required":["using","value"]}
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function findElementsFromElementBy($parentElement, $childData)
    {
        $elements = $this->findElementsFromElement($childData, $parentElement->toWebDriverObject()['ELEMENT']);

        $elements = array_filter($elements, function($webElement) {
            return $this->elementDisplayed($webElement['ELEMENT']);
        });

        //array_values для перестроения ключей
        return array_values(array_map(function($webElement) {
            return $this->elementFromResponseValue($webElement);
        }, $elements));
    }

    /**
     * @param array $data
     * @options {"required":["using","value"]}
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function byDisplayed($data)
    {
        foreach ($this->findElementsBy($data) as $element) {
            if ($element->displayed()) {
                return $element;
            }
        }

        throw new ElementNotFound($data['value']);
    }

    /**
     * @param Closure $closure
     * @param int $timeout
     * @param string $message
     */
    public function waitForElementChange(Closure $closure, $timeout, $message = '')
    {
        $this->until(
            $closure,
            $message,
            $timeout
        );
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @param $timeout
     * @param string $message
     */
    public function waitForElementVisible($element, $timeout = 10, $message = '')
    {
        $this->until(
            function () use ($element) {
                return $element->displayed();
            },
            $message,
            $timeout
        );
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @param $timeout
     * @param string $message
     */
    public function waitForElementNotVisible($element, $timeout = 10, $message = '')
    {
        $this->until(
            function () use ($element) {
                return !$element->displayed();
            },
            $message,
            $timeout
        );
    }

    /**
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     */
    public function seeElement($element)
    {
        $this->assertTrue($element->displayed());
    }

    /**
     * @param array $data
     * @options {"required":["using","value"]}
     */
    public function dontSeeElementBy($data)
    {
        $I = $this;

        try {
            $I->assertFalse($I->findBy($data)->displayed());
        } catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $e) {
            if ($e->getCode() != PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement) {
                throw $e;
            }
        }
    }

    /**
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $select
     * @param string $option
     * @throws NoSuchElementException
     */
    public function selectOption($select, $option)
    {
        $select->click();
        $options = $this->findElementsFromElementBy($select, $this->byXPath('option'));
        foreach ($options as $selectOption) {
            if ($selectOption->text() == $option) {
                $selectOption->click();
            }
        }

        throw new NoSuchElementException("Option '$option' not found in '{$select->getId()}'");
    }

    /**
     * Check that date matches pattern
     *
     * @param string $date
     */
    public function assertDateFormat($date)
    {
        $this->assertRegExp('/^(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})|Вчера|Сегодня$/ui', $date);
    }

    /**
     * @param string $text
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $container
     */
    public function see($text, $container) {
        $this->assertContains($text, $container->text());
    }

    /**
     * @param string $text
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $container
     */
    public function dontSee($text, $container)
    {
        $this->assertNotRegExp("/$text/u", $container->text());
    }

    /**
     * @param string $relativeUrl
     */
    public function seeIAmOnUrl($relativeUrl) {
        $this->assertEquals($relativeUrl, $this->getRelativeUrl());
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $checkbox
     */
    public function seeCheckboxIsChecked($checkbox) {
        $this->assertTrue($checkbox->selected());
    }

    /**
     * @param \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @return bool
     */
    public function hasImgWithSource($element)
    {
        $imageWithSource = ["using" => "xpath", "value" => "//img[@src]"];
        return $this->findElementFromElementBy($element, $imageWithSource)->displayed();
    }

    /**
     * Pauses execution and waits for user input to proceed.
     */
    public function pauseExecution()
    {
        Codeception\Util\Debug::pause();
    }

    /**
     *  Randomly open shop item on page
     */
    public function openRandomProductCard()
    {
        $I = $this;
        $shopItemPage = new ShopItemPage($I);

        $I->findBy($shopItemPage->root);
        $items = $I->findElementsBy($shopItemPage->root);
        $itemName = $I->findElementFromElementBy($items[rand(1, count($items))], $shopItemPage->name);
        codecept_debug($itemName->text());
        $I->verticalSwipeToElement($itemName);
        $itemName->click();
    }

    /**
     * Checks two values ​​for equal with a given error
     *
     * @param $expected
     * @param $actual
     * @param $error
     */
    public function assertEqualsWithPermissibleLimitsOfErrors($expected, $actual, $error)
    {
        $I = $this;

        $I->comment("assertEqualsWithPermissibleLimitsOfErrors: number1:$expected, number2:$actual, error:$error");
        $this->assertTrue(abs($expected - $actual) <= $error);
    }

    /**
     * Add random elements and return array of these elements
     *
     * @param int numberToAdd Number of elements to add
     * @param int maxQuantity Randomly increase number of added items in cart from 1 to maxQuantity
     * @return CartHelper
     */
    public function addRandomDifferentItemsToCart($numberToAdd = 5, $maxQuantity = 1)
    {
        $I = $this;
        $shopItemPage = new ShopItemPage($I);
        $cart = new CartHelper();
        $totalQuantityCircle = $I->findBy((new HeaderPage($I))->totalQuantityCircle);

        $I->amGoingTo("add {$numberToAdd} random different items to cart");
        $shopItems = $I->findElementsBy($shopItemPage->root);
        $numberOfItems = count($shopItems);
        $I->expect('that there is at least one item on the page');
        $I->assertGreaterThanOrEqual(1, $numberOfItems);
        $numberToAdd = $numberToAdd < $numberOfItems ? $numberToAdd : $numberOfItems;
        $itemNumbers = self::uniqueRandomNumbersWithinRange(0, $numberOfItems - 1, $numberToAdd);

        foreach ($itemNumbers as $itemNumber) {
            $shopItem = $shopItems[$itemNumber];

            $I->comment('I choose which item to add');
            $desiredShopItemQuantity = rand(1, $maxQuantity);
            for ($shopItemQuantity = 0; $shopItemQuantity < $desiredShopItemQuantity; $shopItemQuantity++) {
                $oldQuantity = $totalQuantityCircle->text();
                if ($shopItemPage->addToCart($shopItem, $cart)) {
                    $I->waitForElementChange(
                        function () use ($I, $totalQuantityCircle, $oldQuantity) {
                            return $totalQuantityCircle->text() == $oldQuantity;
                        },
                        20
                    );
                    $I->expect("that number of items in cart is changed");
                    $I->assertEquals($totalQuantityCircle->text(), $cart->getTotalItemsQuantity());
                }
            }
        }

        return $cart;
    }

    /**
     * Вертикальный свайп до элемента
     *
     * Зачем в функции нужен свайп? Дело в том, что при вызове функции location() драйвер находит элемент на странице,
     * но выходит так, что этот элемент оказывается в самом верху страницы, под шапкой, и, соответственно, клик до него
     * не доходит. Этим свайпом элемент выдвигается из-под шапки и становится кликабелен
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $element
     * @param int $offsetX
     * @param int $offsetY
     */
    public function verticalSwipeToElement($element, $offsetX = 0, $offsetY = 0)
    {
        if ($element->displayed()) {
            if ($element->location()['y'] <= 200) {
                $this->swipe(0, 100, 0 + $offsetX, 300 - $element->location()['y'] + $offsetY);
                sleep(2);
            } elseif ($element->location()['y'] >= 200) {
//                codecept_debug($element->text());
//                $this->pauseExecution();
                $this->swipe(0, $element->location()['y'], 0, 200);
//                codecept_debug($element->location()['y']);
//                $this->pauseExecution();
            }
        }
    }

    /**
     * Opens the page for the given relative URL
     *
     * @param string $relativeUrl
     */
    public function amOnPage($relativeUrl)
    {
        $I = $this;

        $I->setUrl(['url' => $I->getUrl() . $relativeUrl]);
    }

    /**
     * @param string $oldUrl
     */
    public function waitUrlChange($oldUrl)
    {
        $I = $this;

        // ждем пока изменится url (значит страница прогрузилась)
        $I->waitForElementChange(
            function () use ($I, $oldUrl) {
                return $I->getRelativeUrl() == $oldUrl;
            },
            20
        );
    }

    /**
     * Delete all non-numbers from given string and cast it to integer
     *
     * @param string $string
     * @return int
     */
    public function grabIntFromString($string)
    {
        $string = preg_replace("/\D+/i", "", $string);
        // удаление thinsp
        return (int)preg_replace("/&#?[a-z0-9]{2,8};/i", "", $string);
    }

    /**
     * Marks a test as incomplete
     *
     * @param string $message
     */
    public function incomplete($message = '')
    {
        $this->getScenario()->incomplete($message);
    }

    /**
     * Marks a test as skip
     *
     * @param string $message
     */
    public function skip($message = '')
    {
        $this->getScenario()->skip($message);
    }

    public function getRelativeUrl()
    {
        return parse_url($this->getUrl(), PHP_URL_PATH);
    }
}
