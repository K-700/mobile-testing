<?php

use Facebook\WebDriver\Exception\TimeOutException;
use Helper\Cart;
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
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function findElementsBy($data)
    {
        $elements = $this->findElements($data);

        $elements = array_filter($elements, function($webElement) {
            return $this->elementDisplayed($webElement['ELEMENT']);
        });

        return array_map(function($webElement) {
            return $this->elementFromResponseValue($webElement);
        }, $elements);
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
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element[]
     */
    public function findElementsFromElementBy($parentElement, $childData)
    {
        $elements = $this->findElementsFromElement($childData, $parentElement->toWebDriverObject()['ELEMENT']);

        $elements = array_filter($elements, function($webElement) {
            return $this->elementDisplayed($webElement['ELEMENT']);
        });

        return array_map(function($webElement) {
            return $this->elementFromResponseValue($webElement);
        }, $elements);
    }

    public function seeIAmOnUrl($relativeUrl) {
        $this->assertEquals($relativeUrl, $this->getRelativeUrl());
    }

    /**
     * @param string $text
     * @param \PHPUnit_Extensions_Selenium2TestCase_Element $container
     */
    public function see($text, $container) {
        $this->assertRegExp("/$text/iu", $container->text());
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
    public function waitForElementVisible($element, $timeout, $message = '')
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

    public function grabIntFromString($string)
    {
        $string = preg_replace("/\D+/i", "", $string);
        // удаление thinsp
        return (int)preg_replace("/&#?[a-z0-9]{2,8};/i", "", $string);
    }

    public function openRandomProductCard()
    {
        $I = $this;
        $shopItemPage = new ShopItemPage($I);

        $I->waitForElementVisible($I->by($shopItemPage->root), 20);
        $items = $I->findElementsBy($shopItemPage->root);
        $items[rand(1, count($items))]->click();
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

    public function verticalSwipeToElement(PHPUnit_Extensions_Selenium2TestCase_Element $element)
    {
        if ($element->displayed() && ($element->location()['y'] <= 0)) {
            // Зачем тут нужен свайп? Дело в том, что при вызове функции location() драйвер находит элемент на странице,
            // но выходит так, что этот элемент оказывается под шапкой страницы, и, соответственно, клик до него не доходит
            // этим свайпом элемент выдвигается из-под шапки
            $this->swipe(0, 100, 0, 350);
            sleep(2);
        }
    }

    public function incomplete($message = '')
    {
        $this->getScenario()->incomplete($message);
    }

    public function skip($message = '')
    {
        $this->getScenario()->skip($message);
    }

    public function getRelativeUrl()
    {
        return parse_url($this->getUrl(), PHP_URL_PATH);
    }

    /**
     * Add random elements and return array of these elements
     *
     * @param int numberToAdd Number of elements to add
     * @param int maxQuantity Randomly increase number of added items in cart from 1 to maxQuantity
     * @return Cart
     */
    public function addRandomDifferentItemsToCart($numberToAdd = 5, $maxQuantity = 1)
    {
        $I = $this;
        $shopItemPage = new ShopItemPage($I);
        $cart = new Cart();
        $totalQuantityCircle = $I->by((new HeaderPage($I))->totalQuantityCircle);

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
}
