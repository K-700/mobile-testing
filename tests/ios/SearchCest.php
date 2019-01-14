<?php

use Page\Search\SearchPage;
use Codeception\Example;

class SearchCest
{
    public function _before(\IosTester $I)
    {
        $I->implicitWait(['ms' => 5000]);
        $I->setUrl(['url' => 'http://test-site.com']);
//        $elem = $I->byCssSelector('.mobile-show');
//        $I->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);
    }

    /**
     * Обычный поиск товара
     * Проверяется количество найденных товаров, присутствие запроса в найденных товарах
     *
     * @param IosTester $I
     * @param SearchPage $searchPage
     * @param Example $searchRequest
     * @dataProvider searchRequestProvider
     */
    public function searchTest(\IosTester $I, SearchPage $searchPage, Example $searchRequest)
    {
//        $I->implicitWait(['ms' => 5000]);
//        $I->setUrl(['url' => 'http://test-site.com']);
        $elem = $I->byCssSelector('.mobile-show');
        $I->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);

        sleep(10);
        $searchPage->searchByRequest($searchRequest['request']);
        $searchPage->checkFoundItems($searchRequest['regexp']);
    }

    /**
     * Поиск товара с ошибкой в поисковой фразе
     * Проверяется количество найденных товаров, присутствие запроса в найденных товарах, сообщение с исправленным словом
     *
     * @param Example $searchRequest
     * @param SearchPage $searchPage
     * @dataProvider searchMisspeledRequestsProvider
     */
//    public function misspelSearchTest(Example $searchRequest, SearchPage $searchPage)
//    {
//        sleep(5);
//        $searchPage->searchByRequest($searchRequest['misspel_request']);
//        $searchPage->checkMisspelMessage($searchRequest['misspel_request'], $searchRequest['true_request']);
//        $searchPage->checkFoundItems($searchRequest['regexp']);
//    }
//
//    /**
//     * Поиск несуществующего товара
//     * Проверяется, что товаров не найдено
//     *
//     * @param Example $searchRequest
//     * @param SearchPage $searchPage
//     * @dataProvider searchNonexistentRequestsProvider
//     */
//    public function nonexistentSearchTest(Example $searchRequest, SearchPage $searchPage)
//    {
//        sleep(5);
//        $searchPage->searchByRequest($searchRequest['request']);
//        $searchPage->checkNotFoundItems();
//    }

    protected function searchRequestProvider()
    {
        return [
            [
                'request' => 'синий',
                'regexp' => 'син(ими|е|яя|ее|ие|ий)'
            ]
        ];
    }

    protected function searchMisspeledRequestsProvider()
    {
        return [
            [
                'misspel_request' => 'жолтый',
                'true_request' => 'желтый',
                'regexp' => 'желт(ыми|о|ая|ое|ые|ый)'
            ]
        ];
    }

    protected function searchNonexistentRequestsProvider()
    {
        return [
            [
                'request' => 'несуществующий'
            ]
        ];
    }
}