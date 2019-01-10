<?php

use Page\SearchPage;

class SearchCest
{
    public function _before(\IosTester $I)
    {
        $I->setUrl(['url' => 'https://www.imkosmetik.com']);
        $elem = $I->byCssSelector('.mobile-show');
        $I->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);
        sleep(5);
    }

    public function searchTest(\IosTester $I, SearchPage $searchPage)
    {
        $I->by($searchPage->openInputFieldButton)->click();
        codecept_debug($I->by($searchPage->queryField)->attribute('placeholder'));
        $I->by($searchPage->queryField)->
        $I->assertEquals(1, 1);
    }
}