<?php
namespace Page\MainPage;

class JivositePage
{
    /** @var \IosTester */
    protected $tester;

    /** @var string Root locator */
    public $root;

    public $startChatButton;

    public $closeButton;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ['using' => 'css selector', 'value' => 'jdiv.mobileContainer_2k'];
        $this->startChatButton = ['using' => 'css selector', 'value' => 'jdiv.overlay_2w'];
        $this->closeButton = ['using' => 'css selector', 'value' => 'jdiv.mobileBack_1w'];
    }
}