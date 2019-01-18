<?php
namespace Page;

class MenuPage
{
    protected $tester;

    public $menuButton;

    public $mainMenuTitle;

    /** @var string Root locator */
    public $nav;

    public $tab;

    /** пункт "О нас" */
    private $aboutUrl;
    private $aboutButton;
    private $aboutContent;

    /** пункт "Как заказать" */
    private $howToOrderUrl;
    private $howToOrderHeader;
    private $howToOrderButton;

    /** пункт "Оплата" */
    private $paymentUrl;
    private $paymentHeader;
    private $paymentContent;

    /** пункт "Журнал" */
    private $journalUrl;

    /** пункт "Контакты" */
    private $contactsUrl;
    private $contactsHeader;
    private $contactsContent;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->nav = ["using" => "class name", "value" => "nav"];

        /** пункт "О нас" */
        //TODO: напилить норм тест для этого таба
        $this->aboutUrl = '/about-us/';
        $this->aboutButton = ["using" => "link text", "value" => "О нас"];
        $this->aboutContent = ["using" => "class name", "value" => "about-head"];

        /** пункт "Как заказать" */
        $this->howToOrderUrl = '/how-to-order/';
        $this->howToOrderHeader = 'КАК ОФОРМИТЬ ЗАКАЗ?';
        $this->howToOrderButton = ["using" => "link text", "value" => "Как заказать"];

        /** пункт "Оплата" */
        $this->paymentUrl = '/payment/';
        $this->paymentHeader = 'Оплата осуществляется следующими способами';
        $this->paymentContent = ["using" => "class name", "value" => "adptr-table-scroll"];

        /** пункт "Журнал" */
        $this->journalUrl = '/journal/';

        /** пункт "Контакты" */
        $this->contactsUrl = '/contacts/';
        $this->contactsHeader = 'Наши контакты';
        $this->contactsContent = ["using" => "class name", "value" => "text_content"];
    }

    public function checkAboutNav()
    {
        $I = $this->tester;
        $cacklePage = new CacklePage($I);

        $I->by($this->menuButton)->click();
        $I->by($this->aboutButton)->click();
        $I->seeIAmOnUrl($this->aboutUrl);
        $I->assertEquals($I->by($this->aboutContent)->text(), "imkosmetik — мультибрендовый интернет-магазин профессиональной косметики – поставщик качественной продукции для настоящих профессионалов. Уже более 3-х лет нам доверяют мастера и профессионалы своего дела.");
        $I->by($cacklePage->root)->displayed();
    }

    public function checkHowToOrderTab()
    {
        $I = $this->tester;

        $I->by($this->menuButton)->click();
        $I->by($this->howToOrderButton)->click();
        $I->seeIAmOnUrl($this->howToOrderUrl);
        $I->see($this->howToOrderHeader);
        $I->assertNotEmpty($I->grabTextFrom($this->howToOrderContent));
    }

    public function checkPaymentTab()
    {
        $I = $this->tester;

        $I->click('Оплата', $this->root);
        $I->canSeeInCurrentUrl($this->paymentUrl);
        $I->canSee($this->paymentHeader, $this->paymentContent);
        $I->assertNotEmpty($I->grabTextFrom($this->paymentContent));
    }

    public function checkJournalTab()
    {
        $I = $this->tester;

        $I->click('Журнал', $this->root);
        $I->canSeeInCurrentUrl($this->journalUrl);
        //вернемся обратно на сайт для теста дальнейших табов
        $I->amOnPage('');
    }

    public function checkContactsTab()
    {
        $I = $this->tester;

        $I->click('Контакты', $this->root);
        $I->canSeeInCurrentUrl($this->contactsUrl);
    }
}
