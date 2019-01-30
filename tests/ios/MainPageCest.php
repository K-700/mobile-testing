<?php

// TODO: раскомментить после починки прогрузки retail-rocket
use Page\MainPage\RetailRocketPage;
use Helper\TestUserHelper;
use Page\MainPage\MainPage;
use Codeception\Example;

class MainPageCest
{
    /**
     * Проверка присутствия основных элементов на главной странице
     *
     * @param IosTester $I
     * @param MainPage $mainPage
     */
    public function mainPageTest(IosTester $I, MainPage $mainPage)
    {
        $I->expectTo('see imkosmetik logo');
        $I->hasImgWithSource($I->findBy($mainPage->header->mainLogo));

        $I->expectTo('see full site button');
        $I->seeElement($I->findBy($mainPage->header->fullSiteButton));

        $I->expectTo('see main slider');
        $I->seeElement($I->findBy($mainPage->mainSlider));

        $I->expectTo('see stocks');
        $I->assertGreaterThanOrEqual(1, count($mainPage->getStocks()));

        $I->expectTo('see novelties');
        $I->assertGreaterThanOrEqual(1, count($mainPage->getNovelties()));

        $I->expectTo('see links to social networks in footer');
        $I->assertGreaterThanOrEqual(1, count($mainPage->footer->getSocialsLinks()));

        $I->expectTo('see phone in footer');
        $I->seeElement($I->findBy($mainPage->footer->phone));

        $I->expectTo('see mail in footer');
        $I->seeElement($I->findBy($mainPage->footer->mail));

        $I->expectTo('see payment methods in footer');
        $I->hasImgWithSource($I->findBy($mainPage->footer->payContainer));

        $I->expectTo('see full site button in footer');
        $I->seeElement($I->findBy($mainPage->footer->fullVersionLink));
    }

    /**
     * Проверка работы формы подписки (в футере)
     *
     * @param IosTester $I
     * @param Example $userData
     * @param MainPage $mainPage
     * @dataProvider userDataProvider
     */
    public function subscribeFormTest(IosTester $I, Example $userData, MainPage $mainPage)
    {
        $testUser = new TestUserHelper($userData);
        $subscribeForm = $mainPage->subscribeForm;

        $subscribeForm->invalidSubscribe();
        sleep(5);
        $I->dontSee($mainPage->subscribeForm->successMessage, $I->findBy($subscribeForm->root));

        $subscribeForm->subscribe($testUser);
        $I->see($mainPage->subscribeForm->successMessage, $I->findBy($subscribeForm->root));
    }

//    TODO: раскомментить после починки прогрузки retail-rocket
    /**
     * Проверка виджета retail-rocket
     * Проверяется наличие карточек с товарами на главной странице
     *
     * @param IosTester $I
     * @throws Exception
     */
    public function RetailRocketTest(IosTester $I)
    {
        $I->expectTo('see 12 items in retailrocket \'Хит!\'');
        $retailRocketPage = new RetailRocketPage($I, 'Хит');
        $I->assertEquals(count($retailRocketPage->getWidgetItems()), 12);

        $I->expectTo('see 12 items in retailrocket \'Новинки!\'');
        $retailRocketPage = new RetailRocketPage($I, 'Новинки');
        $I->assertEquals(count($retailRocketPage->getWidgetItems()), 12);

        $I->expect('that no items in retailrocket \'Вас заинтересуют\'');
        $retailRocketPage = new RetailRocketPage($I, 'Вас заинтересуют');
        $I->assertEquals(count($retailRocketPage->getWidgetItems()), 0);
        $retailRocketPage->triggerRetailRocket();
        $I->expectTo('see 4 items in retailrocket \'Вас заинтересуют\'');
        $I->assertEquals(count($retailRocketPage->getWidgetItems()), 4);
    }

    protected function userDataProvider()
    {
        return [
            [
                'name' => 'Владимир Владимирович Краб',
                'mail' => 'krab@mail.ru'
            ]
        ];
    }
}

