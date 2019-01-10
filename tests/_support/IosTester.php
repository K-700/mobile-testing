<?php

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
}
