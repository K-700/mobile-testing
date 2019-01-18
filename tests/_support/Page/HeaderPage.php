<?php
namespace Page;

class HeaderPage
{
    protected $tester;

    public $root;

    public $basketButton;

    public $totalQuantityCircle;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ['using' => 'class name', 'value' => 'header__main'];
        $this->totalQuantityCircle = ['using' => 'class name', 'value' => 'quantity-badge'];
        $this->basketButton = ['using' => 'class name', 'value' => 'header__cart'];
    }
}
