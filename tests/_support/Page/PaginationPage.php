<?php

namespace Page;

class PaginationPage
{
    /** @var \IosTester */
    protected $tester;

    /** @var string Root locator */
    public $root;

    public $backwardButton;

    public $pages;

    public $page;

    public $forwardButton;

    public $firstPage;

    public $lastPage;

    public $currentPage;

    /** @var int сколько прибавляется/убавляется страниц при нажатии на кнопки Вперед/Назад */
    public $buttonAddSize;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;
        $this->buttonAddSize = 3;

        $this->root = ['using' => 'class name', 'value' => 'paging'];
        $this->backwardButton = ['using' => 'class name', 'value' => "glyphicon-menu-left"];
        $this->pages = ['using' => 'class name', 'value' => 'paging-items'];
        $this->forwardButton = ['using' => 'class name', 'value' => "glyphicon-menu-right"];
        $this->page = ['using' => 'class name', 'value' => 'page_link'];
        $this->currentPage = ['using' => 'class name', 'value' => 'current'];
    }

    public function getPages()
    {
        $I = $this->tester;

        return $I->findElementsFromElementBy($I->findBy($this->pages), $this->page);
    }

    /**
     * @return int
     */
    public function getFirstPage()
    {
        return (int)$this->getPages()[0]->text();
    }

    /**
     * @return int
     */
    public function getLastPage()
    {
        return (int)end($this->getPages())->text();
    }

    public function getCurrentPage()
    {
        $I = $this->tester;

        return (int)$I->findElementFromElementBy($I->findBy($this->pages), $this->currentPage)->text();
    }
}