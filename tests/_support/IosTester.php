<?php

use Facebook\WebDriver\Exception\TimeOutException;
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
     * @param array $data
     * @options {"required":["using","value"]}
     * @return PHPUnit_Extensions_Selenium2TestCase_Element[]
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
     * @param PHPUnit_Extensions_Selenium2TestCase_Element $parentElement
     * @param $childData
     * @options {"required":["using","value"]}
     * @return PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function findElementFromElementBy(PHPUnit_Extensions_Selenium2TestCase_Element $parentElement, $childData)
    {
        return $this->elementFromResponseValue($this->findElementFromElement($childData, $parentElement->toWebDriverObject()['ELEMENT']));
    }

    /**
     * @param $data
     * @param $timeout
     * @param string $message
     * @throws TimeOutException
     * @throws \Exception
     */
    public function waitForElementVisible($data, $timeout, $message = '')
    {
        $end = microtime(true) + $timeout;
        $last_exception = null;

        while ($end > microtime(true)) {
            try {
                 if ($this->by($data)->displayed()) {
                     return;
                 }
            } catch (Exception $e) {
                $last_exception = $e;
            }
        }

        if ($last_exception) {
            throw $last_exception;
        }

        throw new TimeOutException($message);
    }

    public function hasImgWithSource(PHPUnit_Extensions_Selenium2TestCase_Element $element)
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
        return (int)preg_replace("/\D+/", "", $string);
    }

    public function openRandomProductCard()
    {
        $I = $this;
        $shopItemPage = new ShopItemPage($I);

        $I->waitForElementVisible($shopItemPage->root, 20);
        $items = $I->findElementsBy($shopItemPage->root);
        $items[rand(1, count($items))]->click();
        $I->pauseExecution();
    }

}
