<?php

use Page\Menu\Navs\NavsPage;
use Page\PaginationPage;

class PaginationCest
{
    /**
     * Тест пагинатора. Считается, что пагинатор виден (есть хотя-бы 2 страницы), иначе тест скипнется.
     * Проверяются кнопки перехода на первую и последнюю страницы, переход на страницу. Тест на странице новостей
     *
     * @param IosTester $I
     * @param NavsPage $navsPage
     * @param PaginationPage $paginationPage
     */
    public function paginationTest(IosTester $I, NavsPage $navsPage, PaginationPage $paginationPage)
    {
        $I->findBy($navsPage->menuButton)->click();
        $I->findBy($navsPage->newsButton)->click();

        $I->verticalSwipeToElement($I->findBy($paginationPage->root));
        $pages = $paginationPage->getPages();
        if (count($pages) == 0) {
            $I->skip('Paging not found');
        }
        if (count($pages) == 1) {
            $I->incomplete('Paging has only 1 page. Nothing to test.');
        }

        $I->assertEquals($paginationPage->getCurrentPage(), 1);
        $I->dontSeeElementBy($paginationPage->backwardButton);

        // проверка кнопки "Вперед"
        if ($paginationPage->getLastPage() > $paginationPage->buttonAddSize) {
            $I->amGoingTo('check button "Вперёд"');
            $I->verticalSwipeToElement($I->findBy($paginationPage->root));
            $I->dontSeeElementBy($paginationPage->forwardButton);
            $oldPageNumber = $paginationPage->getCurrentPage();
            $I->findBy($paginationPage->forwardButton)->click();
            $I->expect("that i moved $paginationPage->buttonAddSize pages ahead");
            $I->assertEquals($oldPageNumber + $paginationPage->buttonAddSize, $paginationPage->getCurrentPage());
            $I->seeElement($I->findBy($paginationPage->backwardButton));
        }

        // проверка последней страницы
        if ($paginationPage->getLastPage() != $paginationPage->getCurrentPage()) {
            $I->verticalSwipeToElement($I->findBy($paginationPage->root));
            $I->findBy($paginationPage->lastPage)->click();
            $I->expect('i\'m on the last page');
            $I->dontSeeElementBy($$paginationPage->forwardButton);
            $I->assertEquals($paginationPage->getLastPage(), $paginationPage->getCurrentPage());
        }

        // проверка кнопки "Назад"
        if ($paginationPage->getLastPage() > $paginationPage->buttonAddSize) {
            $I->amGoingTo('check button "Назад"');
            $I->findBy($paginationPage->backwardButton)->click();
            $I->verticalSwipeToElement($I->findBy($paginationPage->root));
            $I->expect("that i moved $paginationPage->buttonAddSize pages backward");
            $oldPageNumber = $paginationPage->getCurrentPage();
            $I->assertEquals($oldPageNumber - $paginationPage->buttonAddSize, $paginationPage->getCurrentPage());
            $I->seeElement($I->findBy($paginationPage->forwardButton));
        }

        // проверка первой страницы
        if ($paginationPage->getFirstPage() != $paginationPage->getCurrentPage()) {
            $I->click($paginationPage->firstPage);
            $I->verticalSwipeToElement($I->findBy($paginationPage->root));
            $I->expect('i\'m on the first page');
            $I->dontSeeElementBy($paginationPage->backwardButton);
            $I->assertEquals($paginationPage->getCurrentPage(), $paginationPage->getFirstPage());
        }
    }
}

