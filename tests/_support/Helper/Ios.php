<?php
namespace Helper;

use Appium\AppiumDriver;
use Codeception\Exception\ModuleException;

class Ios extends \Codeception\Module
{
    /**
     * @return string
     * @throws ModuleException
     */
    public function getSiteUrl()
    {
        /** @var AppiumDriver $driver */
        $driver = $this->getModule('\Appium\AppiumDriver');

        return $driver->_getConfig('url');
    }

    /**
     * @param array $data
     * @options {"required":["using","value"]}
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element
     * @throws ModuleException
     */
    public function findBy($data)
    {
        /** @var AppiumDriver $driver */
        $driver = $this->getModule('\Appium\AppiumDriver');

        return $driver->TestCaseElm()->by($data['using'], $data['value']);
    }

    /**
     * @param array $value WebElement JSON object
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     * @throws ModuleException
     */
    public function elementFromResponseValue($value)
    {
        /** @var AppiumDriver $driver */
        $driver = $this->getModule('\Appium\AppiumDriver');

        return $driver->getSession()->elementFromResponseValue($value);
    }
}
