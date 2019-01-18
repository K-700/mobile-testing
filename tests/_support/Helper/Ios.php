<?php
namespace Helper;

use Codeception\Exception\ModuleException;

class Ios extends \Codeception\Module
{
    /**
     * @param array $data
     * @options {"required":["using","value"]}
     * @return \Appium\TestCase\Element|\PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function by($data)
    {
//        try {
            return $this->getModule('\Appium\AppiumDriver')->TestCaseElm()->by($data['using'], $data['value']);
//        } catch (ModuleException $e) {
//            codecept_debug('ModuleException');
//            // TODO: подумать как нормально обработать
//            // throw new SkippedTestError($e->getMessage());
//        }
    }

    /**
     * @param array $value WebElement JSON object
     *
     * @return \PHPUnit_Extensions_Selenium2TestCase_Element
     */
    public function elementFromResponseValue($value)
    {
        try {
            return $this->getModule('\Appium\AppiumDriver')->getSession()->elementFromResponseValue($value);
        } catch (ModuleException $e) {
            // TODO: подумать как нормально обработать
            // throw new SkippedTestError($e->getMessage());
        }
    }
}
