<?php

use Page\Ios\Menu\Navs\NavsPage;
use Page\Ios\Menu\Navs\DeliveryNav\DeliveryMethod;
use Page\Ios\Menu\Navs\DeliveryNav\DeliveryPage;
use Page\Ios\Menu\Navs\NewsNav\NewsPage;
use Codeception\Example;

class MenuCest
{
    /**
     * Тест проверяет табы (кроме таба "Доставка")
     * Проверяется URL, присутствие какого-либо текста (не уточняется какого именно (кроме заголовков, без них вывод ошибки тоже будет считаться за какой-либо текст), по сути проверка на непустоту контента)
     *
     * @param NavsPage $navsPage
     */
    public function menuMainNavsTest(NavsPage $navsPage)
    {
        $navsPage->checkAboutNav();
        $navsPage->checkHowToOrderNav();
        $navsPage->checkPaymentNav();
        $navsPage->checkJournalNav();
        $navsPage->checkContactsNav();
    }

    /**
     * Проверка страницы с доставками
     *
     * @param IosTester $I
     * @param NavsPage $navsPage
     * @param Example $cityProvider
     * @param DeliveryPage $deliveryPage
     * @dataProvider detectableCityProvider
     */
    public function deliveryTabTest(IosTester $I, NavsPage $navsPage, Example $cityProvider, DeliveryPage $deliveryPage)
    {
        $I->findBy($navsPage->menuButton)->click();
        $I->findBy($navsPage->deliveryButton)->click();
        $I->seeIAmOnUrl($navsPage->deliveryUrl);
//        if ($cityProvider['city_type'] == 'detectable') {
            $deliveryPage->inputDetectableCity($cityProvider['region'], $cityProvider['city']);
//        } else {
//            $deliveryPage->inputUndetectableCity($cityProvider['region'], $cityProvider['city']);
//        }

//        $I->waitAllScripts();
        $I->assertEquals($cityProvider['city'], $I->findBy($deliveryPage->selectedCity)->text());

        foreach ($cityProvider['deliveries'] as $delivery) {
            $deliveryMethod = new DeliveryMethod($I, $delivery);
            $deliveryMethod->checkDeliveryContainer();
            $deliveryMethod->checkPickupPoints($cityProvider['city']);
        }
    }

    /**
     * Тест новостных записей на странице новостей
     * Проверяется количество, дата, содержание(на непустоту)
     *
     * @param IosTester $I
     * @param NavsPage $navsPage
     * @param NewsPage $newsPage
     */
    public function newsNavCest(IosTester $I, NavsPage $navsPage, NewsPage $newsPage)
    {
        $I->findBy($navsPage->menuButton)->click();
        $I->findBy($navsPage->newsButton)->click();

        $I->expectTo('see at least one news on the news page');
        $news = $I->findElementsFromElementBy($I->findBy($newsPage->root), $newsPage->newsContainer);
        $I->assertGreaterThanOrEqual(1, count($news));
        foreach ($news as $oneNews) {
            $I->assertNotEmpty($I->findElementFromElementBy($oneNews, $newsPage->newsTitle)->text());
            $I->assertDateFormat($I->findElementFromElementBy($oneNews, $newsPage->newsDate)->text());
        }
    }

    protected function detectableCityProvider()
    {
        return [
            [
                'region' => 'Челябинская область',
                'city' => 'Челябинск',
                'city_type' => 'detectable',
                'deliveries' => ['sdek', 'dpd', 'courier', 'mail', 'imkosmetik_shop']
            ],
//            [
//                'region' => 'Астраханская область',
//                'city' => 'Нариманов',
//                'city_type' => 'detectable',
//                'deliveries' => ['courier', 'mail']
//            ],
//            [
//                'region' => 'Сахалинская область',
//                'city' => 'Кукуево',
//                'city_type' => 'undetectable',
//                'deliveries' => ['mail']
//            ]
        ];
    }
}
