<?php
namespace Page\MainPage;


class MainPage
{
    /** @var \IosTester */
    protected $tester;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

    }
}