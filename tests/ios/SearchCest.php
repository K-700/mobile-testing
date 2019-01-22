<?php

use Page\HeaderPage;
use Page\Search\SearchPage;
use Codeception\Example;

class SearchCest
{
    /**
     * Обычный поиск товара
     * Проверяется количество найденных товаров, присутствие запроса в найденных товарах
     *
     * @param HeaderPage $headerPage
     * @param SearchPage $searchPage
     * @param Example $searchRequest
     * @dataProvider searchRequestProvider
     */
    public function searchTest(HeaderPage $headerPage, SearchPage $searchPage, Example $searchRequest)
    {
        $headerPage->searchByRequest($searchRequest['request']);
        $searchPage->checkFoundItems($searchRequest['regexp']);
    }

    /**
     * Поиск товара с ошибкой в поисковой фразе
     * Проверяется количество найденных товаров, присутствие запроса в найденных товарах, сообщение с исправленным словом
     *
     * @param HeaderPage $headerPage
     * @param SearchPage $searchPage
     * @param Example $searchRequest
     * @dataProvider searchMisspeledRequestsProvider
     */
    public function misspelSearchTest(HeaderPage $headerPage, SearchPage $searchPage, Example $searchRequest)
    {
        $headerPage->searchByRequest($searchRequest['misspel_request']);
        $searchPage->checkMisspelMessage($searchRequest['misspel_request'], $searchRequest['true_request']);
        $searchPage->checkFoundItems($searchRequest['regexp']);
    }

    /**
     * Поиск несуществующего товара
     * Проверяется, что товаров не найдено
     *
     * @param HeaderPage $headerPage
     * @param SearchPage $searchPage
     * @param Example $searchRequest
     * @dataProvider searchNonexistentRequestsProvider
     */
    public function nonexistentSearchTest(HeaderPage $headerPage, SearchPage $searchPage, Example $searchRequest)
    {
        $headerPage->searchByRequest($searchRequest['request']);
        $searchPage->checkNotFoundItems();
    }

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