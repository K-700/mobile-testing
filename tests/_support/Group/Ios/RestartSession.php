<?php
namespace Group\Ios;

use Appium\AppiumDriver;
use \Codeception\Event\TestEvent;

/**
 * Group class is Codeception Extension which is allowed to handle to all internal events.
 * This class itself can be used to listen events for test execution of one particular group.
 * It may be especially useful to create fixtures data, prepare server, etc.
 *
 * INSTALLATION:
 *
 * To use this group extension, include it to "extensions" option of global Codeception config.
 */

/**
 * Class RestartSession
 * @package Group
 *
 * Подключение этой группы необходимо тестам, для которых критичен запуск с чистыми данными
 */
class RestartSession extends \Codeception\Platform\Group
{
    public static $group = 'restartSession';

    public function _before(TestEvent $e)
    {
        /** @var AppiumDriver $driver */
        $driver = $this->getModule('\Appium\AppiumDriver');

        // рестарт сессии нужен для сброса кук, по другому хз как сделать
        // еще неплохо было бы, если можно было бы проверить первый ли это тест в suit'е, чтобы не перезапускать для него
        $driver->_closeSession($driver->getSession());
        $driver->_initializeSession();
        // TODO: тут идет повторение HooksExtension.beforeSuite. Было бы красиво вытащить эту функцию оттуда, но у меня не вышло
        $driver->implicitWait(['ms' => 10000]);
        $driver->setUrl(['url' => $driver->_getConfig('url')]);
        sleep(10);
        $elem = $driver->byCssSelector('.mobile-show');
        $driver->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);
    }
}
