<?php
namespace Page;

// Page Object для всплывающего окна с сообщением о том, что товар закончился
class ItemIsOutOfStockPage
{
    protected $tester;

    public $root;

    public $closeButton;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ['using' => 'id', 'value' => 'availability-notification-form'];
        $this->closeButton = ['using' => 'class name', 'value' => 'imk-icon-close'];
    }

    public function closeWindow()
    {
        $I = $this->tester;

        $I->findElementFromElementBy($I->findBy($this->root), $this->closeButton)->click();
    }
}
