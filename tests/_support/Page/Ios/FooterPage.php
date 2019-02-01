<?php
namespace Page\Ios;

class FooterPage
{
    protected $tester;

    public $root;

    public $socialsContainer;
    public $socialsLink;

    public $payContainer;

    public $phone;

    public $mail;

    public $fullVersionLink;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ['using' => 'class name', 'value' => 'footer'];

        $this->socialsContainer = ['using' => 'class name', 'value' => 'footer_social'];
        $this->socialsLink = ['using' => 'xpath', 'value' => '//a'];

        $this->payContainer = ['using' => 'class name', 'value' => 'footer_social'];

        $this->phone = ['using' => 'class name', 'value' => 'phone-b'];

        $this->mail = ['using' => 'class name', 'value' => 'footer__mail'];

        $this->fullVersionLink = ['using' => 'class name', 'value' => 'mobile-fullversion-link'];
    }

    public function getSocialsLinks()
    {
        $I = $this->tester;

        return $I->findElementsFromElementBy($I->findBy($this->socialsContainer), $this->socialsLink);
    }
}
