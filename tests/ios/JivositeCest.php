<?php

use Page\Ios\MainPage\JivositePage;

class JivositeCest
{
    /**
     * Проверка того, что виджет jivosite сам разворачивается до окончания определенного промежутка времени (2 мин.)
     *
     * @group resetSession
     * @param IosTester $I
     * @param JivositePage $jivositePage
     * @throws Exception
     */
    public function waitForJivositeToExpand(IosTester $I, JivositePage $jivositePage)
    {
        $I->dontSeeElementBy($jivositePage->root);
        $I->waitForElementVisible($I->findBy($jivositePage->root), 120);
    }

    /**
     * Проверка того, что виджет jivosite разворачивается и сворачивается при нажатии кнопок
     *
     * @param IosTester $I
     * @param JivositePage $jivositePage
     * @throws Exception
     */
    public function jivositeExpand(IosTester $I, JivositePage $jivositePage)
    {
        $I->findBy($jivositePage->startChatButton)->click();
        $I->seeElement($I->findBy($jivositePage->root));
        $I->findBy($jivositePage->closeButton)->click();
        sleep(3);
        $I->dontSeeElementBy($jivositePage->root);
        $I->seeElement($I->findBy($jivositePage->startChatButton));
    }
}

