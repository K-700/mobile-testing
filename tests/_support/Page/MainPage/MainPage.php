<?php
namespace Page\MainPage;


class MainPage
{
    /** @var \IosTester */
    protected $tester;

//    public $header;
//
//    public $banner;
//
//    public $mainSlider;
//
//    public $metro;
//
//    public $logo;
//
//    public $contacts;
//
//    public $footerSocial;
//
//    public $footerFeedback;
//
//    public $footerPaymentMethodsAndSections;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;
    }
}