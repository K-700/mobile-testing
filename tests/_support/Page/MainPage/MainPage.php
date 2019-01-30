<?php
namespace Page\MainPage;

use Page\FooterPage;
use Page\HeaderPage;
use Page\SubscribePage;

class MainPage
{
    /** @var \IosTester */
    protected $tester;

    /** @var HeaderPage */
    public $header;

    /** @var SubscribePage */
    public $subscribeForm;

    public $footer;

    public $mainSlider;

    public $stockTab;
    public $stock;

    public $noveltyTab;
    public $novelty;

    public function __construct(\IosTester $I, HeaderPage $headerPage, SubscribePage $subscribePage, FooterPage $footerPage)
    {
        $this->tester = $I;
        $this->header = $headerPage;
        $this->subscribeForm = $subscribePage;
        $this->footer = $footerPage;

        $this->mainSlider = ['using' => 'class name', 'value' => 'main-slider'];

        $this->stockTab = ['using' => 'xpath', 'value' => "//div[@data-metro='stock']"];
        $this->stock = ['using' => 'class name', 'value' => 'stock'];

        $this->noveltyTab = ['using' => 'xpath', 'value' => "//div[@data-metro='novelty']"];
        $this->novelty = ['using' => 'class name', 'value' => 'novelty'];
    }

    public function getNovelties()
    {
        $I = $this->tester;

        $noveltyTab = $I->findBy($this->noveltyTab);
        $I->verticalSwipeToElement($noveltyTab);
        $noveltyTab->click();
        return $I->findElementsBy($this->novelty);
    }

    public function getStocks()
    {
        $I = $this->tester;

        $stockTab = $I->findBy($this->stockTab);
        $I->verticalSwipeToElement($stockTab);
        $stockTab->click();
        return $I->findElementsBy($this->stock);
    }
}