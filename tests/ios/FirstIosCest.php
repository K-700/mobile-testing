<?php

use Page\MenuPage;

class FirstIosCest
{
//    public function lockPhone(\IosTester $I, \AcceptanceTester $acceptanceTester)
//    {
//        $I->setUrl(['url' => 'https://www.imkosmetik.com']);
//        $elem = $I->byCssSelector('.mobile-show');
//        $I->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);
////        $elem = $I->byXPath("//span[contains(.,'Хиты')]");
//////        $elem = $I->byCssSelector('.menu-burger');
//        $I->swipe(20, 600, 20, 20);
//        $I->swipe(20, 300, 20, 20);
////        sleep(10);
////        $I->tap([[$elem->location()['x'], $elem->location()['y'] + 70]]);
////        sleep(2);
////        $elem = $I->byXPath("//span[contains(.,'Вас заинтересует')]");
////        $I->tap([[$elem->location()['x'], $elem->location()['y'] + 70]]);
////        $elem = $I->findElement(['using' => 'class name','value' => "mobile-show"]);
////        $I->tap([[87.0, 600.703125]]);
//        sleep(5);
//        $I->click($I->byXPath("//span[contains(.,'Хиты')]")->getId());
////        $elem = $I->findElements(["using" => "class name", "value" => "item"])[0]['ELEMENT'];
//        $count = 0;
//        foreach ($I->findElements(["using" => "class name", "value" => "item"]) as $webDriverObject) {
//            if ($I->elementDisplayed($webDriverObject['ELEMENT'])) {
//                $count++;
//                $item_title = $I->findElementFromElement(["using" => "class name", "value" => "item-title"], $webDriverObject['ELEMENT']);
//                $id = $item_title['ELEMENT'];
//                codecept_debug($I->getText($id));
//            }
//        }
//
//
//        codecept_debug($count);
//        sleep(5);
//        $I->click($I->byXPath("//span[contains(.,'Вас заинтересует')]")->getId());
//
////        $elem = $I->byCssSelector('.search-small-form');
////        $I->click($I->byClassName('.mobile-show')->getId());
////        $x = (int)$elem->location()['x'];
////        $y = (int)$elem->location()['y'];
////        codecept_debug($x);
////        codecept_debug($y);
////        codecept_debug($elem);
//
////        $I->setContext(["name" => $I->getContexts()[0]]);
////        codecept_debug($I->getCurrentContext());
////        $params = array(array('x' => $x, 'y' => $y));
////        $I->tap([[$x, $y]]);
////        codecept_debug($I->getCurrentContext());
////        codecept_debug($I->getContexts());
//
////        codecept_debug($I->getWindowHandles());
////        $size = $I->getWindowSize((float) $I->getWindowHandle());
////        codecept_debug($elem->getId());
////        $I->click($elem->getId());
////        codecept_debug($size);
////        $I->setContext(["name" => $I->getContexts()[1]]);
//
//
//
////        codecept_debug($elem);
////        codecept_debug($elem->toWebDriverObject());
////        $size = $elem->size();
//
////        $centerX = $size['width'] / 2 + $elem->location()['x'];
////        $centerY = $size['height'] / 2 + $elem->location()['y'];
////        $I->setContext(["name" => $I->getContexts()[0]]);
////        $I->tap([[$x, $y]]);
////        $I->swipe(20, 400, 20, 20);
////        $I->setContext(["name" => $I->getContexts()[1]]);
////        $params = array(array('x' => $elem->location()['x'], 'y' => $elem->location()['y']));
////        $I->byCssSelector('.mobile-show')->click();
////        codecept_debug($I->byCssSelector('.mobile-show')->size());
////        codecept_debug($I->byCssSelector('.allbrands')->size());
////        $params = array(array('x' => $x, 'y' => $y - 80));
////        $I->execute(array(
////            'script' => 'mobile: tap',
////            'args' => $params,
////        ));
//        sleep(5);
////        $this->assertEquals($elem->attribute('value'), 'Подписаться');
//        $I->assertEquals(1, 1);
//    }

    public function menuTest(\IosTester $I, MenuPage $menuPage)
    {
        $I->setUrl(['url' => 'http://test-site.com']);
        $elem = $I->byCssSelector('.mobile-show');
        $I->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);
        sleep(5);
        $I->by($menuPage->menuButton)->click();
        $I->assertEquals(1, 1);

        $menuTitles = $I->findElementsBy($menuPage->mainMenuTitle);
        $I->assertGreaterOrEquals(1, count($menuTitles));
        foreach ($menuTitles as $menuTitle) {
            $I->assertNotEmpty($menuTitle->text());
        }
    }
}