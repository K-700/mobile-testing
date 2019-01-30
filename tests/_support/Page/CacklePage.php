<?php
namespace Page;

class CacklePage
{
    /** @var \IosTester */
    protected $tester;

    /** @var array Root locator */
    public $root;

    public $nextCommentsButton;

    public $comment;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ['using' => 'class name', 'value' => 'mc-c'];
        $this->nextCommentsButton = ['using' => 'class name', 'value' => 'mc-comment-next'];
        $this->comment = ['using' => 'class name', 'value' => 'mc-comment-info'];
    }

    public function openAllComments()
    {
        $I = $this->tester;

        $nextCommentsButton = $I->findBy($this->nextCommentsButton);
        while ($nextCommentsButton->displayed()) {
            $I->verticalSwipeToElement($nextCommentsButton);
            $nextCommentsButton->click();
        }
    }

    public function getNumberOfComments()
    {
        $I = $this->tester;

        return count($I->findElementsBy($this->comment));
    }
}