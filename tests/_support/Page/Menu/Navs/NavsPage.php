<?php
namespace Page\Menu\Navs;

// TODO: исправить
use Page\CacklePage;
use Page\Menu\MenuPage;

class NavsPage extends MenuPage
{
    /** пункт "О нас" */
    private $aboutUrl;
    private $aboutButton;
    private $aboutContent;

    /** пункт "Как заказать" */
    private $howToOrderUrl;
    private $howToOrderContent;
    private $howToOrderButton;

    /** пункт "Оплата" */
    private $paymentUrl;
    private $paymentContent;
    private $paymentButton;

    /** пункт "Журнал" */
    private $journalUrl;
    private $journalButton;

    /** пункт "Доставка" */
    public $deliveryUrl;
    public $deliveryButton;

    /** пункт "Контакты" */
    private $contactsUrl;
    private $contactsButton;
    private $contactsContent;

    /** пункт "Новости" */
    public $newsButton;

    public function __construct(\IosTester $I)
    {
        parent::__construct($I);

        /** пункт "О нас" */
        //TODO: напилить норм тест для этого таба
        $this->aboutUrl = '/about-us/';
        $this->aboutContent = ["using" => "class name", "value" => "about-head"];
        $this->aboutButton = ["using" => "link text", "value" => "О нас"];

        /** пункт "Как заказать" */
        $this->howToOrderUrl = '/how-to-order/';
        $this->howToOrderContent = ["using" => "class name", "value" => "text_content"];
        $this->howToOrderButton = ["using" => "link text", "value" => "Как заказать"];

        /** пункт "Оплата" */
        $this->paymentUrl = '/payment/';
        $this->paymentContent = ["using" => "class name", "value" => "page-content"];
        $this->paymentButton = ["using" => "link text", "value" => "Оплата"];

        /** пункт "Журнал" */
        $this->journalUrl = '/journal/';
        $this->journalButton = ["using" => "link text", "value" => "Журнал"];

        /** пункт "Доставка" */
        $this->deliveryUrl = '/shipping/';
        $this->deliveryButton = ["using" => "link text", "value" => "Доставка"];

        /** пункт "Контакты" */
        $this->contactsUrl = '/contacts/';
        $this->contactsButton = ["using" => "link text", "value" => "Контакты"];
        $this->contactsContent = ["using" => "class name", "value" => "text_content"];

        /** пункт "Новости" */
        $this->newsButton = ["using" => "link text", "value" => "Новости"];
    }

    public function checkAboutNav()
    {
        $I = $this->tester;
        $cacklePage = new CacklePage($I);

        $I->findBy($this->menuButton)->click();
        $I->findBy($this->aboutButton)->click();
        $I->seeIAmOnUrl($this->aboutUrl);
        $I->see("imkosmetik — мультибрендовый интернет-магазин профессиональной косметики – поставщик качественной продукции для настоящих профессионалов. Уже более 3-х лет нам доверяют мастера и профессионалы своего дела.", $I->findBy($this->aboutContent));
        $I->findBy($cacklePage->root)->displayed();
    }

    public function checkHowToOrderNav()
    {
        $I = $this->tester;

        $I->findBy($this->menuButton)->click();
        $I->findBy($this->howToOrderButton)->click();
        $I->seeIAmOnUrl($this->howToOrderUrl);
        $I->see('КАК ОФОРМИТЬ ЗАКАЗ?', $I->findBy($this->howToOrderContent));
    }

    public function checkPaymentNav()
    {
        $I = $this->tester;

        $I->findBy($this->menuButton)->click();
        $I->findBy($this->paymentButton)->click();
        $I->seeIAmOnUrl($this->paymentUrl);
        $I->see('Оплата осуществляется следующими способами', $I->findBy($this->paymentContent));
    }

    public function checkJournalNav()
    {
        $I = $this->tester;

        $I->findBy($this->menuButton)->click();
        $I->findBy($this->journalButton)->click();
        $I->seeIAmOnUrl($this->journalUrl);
        //вернемся обратно на сайт для теста дальнейших табов
        //TODO: впилить редирект на сайт
    }

    public function checkContactsNav()
    {
        $I = $this->tester;

        $I->findBy($this->menuButton)->click();
        $I->findBy($this->contactsButton)->click();
        $I->seeIAmOnUrl($this->contactsUrl);
    }
}
