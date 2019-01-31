<?php
namespace Extension;

use Appium\AppiumDriver;
use Codeception\Event\SuiteEvent;
use \Codeception\Events;

class IosExtension extends \Codeception\Extension
{
    public static $events = array(
        Events::SUITE_BEFORE => 'beforeSuite'
    );

    /**
     * Подготовка набора тестов для начала мобильного тестированияю Вызывается перед каждым набором тестов.
     *
     * @param SuiteEvent $e
     * @throws \Codeception\Exception\ModuleRequireException
     */
    public function beforeSuite(SuiteEvent $e) {
        /** @var AppiumDriver $driver */
        $driver = $this->getModule('\Appium\AppiumDriver');

        $driver->implicitWait(['ms' => 10000]);
        $driver->setUrl(['url' => $driver->_getConfig('url')]);
        sleep(10);
        $elem = $driver->byCssSelector('.mobile-show');
        $driver->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);
    }
}