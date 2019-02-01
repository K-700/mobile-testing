<?php
namespace Page\Ios;

class LoadPage
{
    protected $tester;

    public $root;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ['using' => 'class name', 'value' => 'loader'];
    }

    /**
     * @return bool Нахоидтся ли страница в загрузке
     */
    public function isPageLoading()
    {
        $I = $this->tester;

        return $I->elementExistAndDisplayed($this->root);
    }
}
