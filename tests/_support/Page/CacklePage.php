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

        while ($I->by($this->nextCommentsButton)->displayed()) {
//            * @options {"optional":["element","xoffset","yoffset"]}
            $element = $I->findElement($this->nextCommentsButton);
            $I->pauseExecution();
            $I->moveTo(["element" => $element["ELEMENT"], "xoffset" => "0", "yoffset" => "-70"]);
            $I->pauseExecution();
            $I->by($this->nextCommentsButton)->click();
            // TODO: возможно стоит завязаться не просто на ожидание некоторого количества времени, но пока не придумала ничего лучше
//            $I->pauseExecution();
            sleep(2);
        }
    }

    public function getNumberOfComments()
    {
        $I = $this->tester;

        return count($I->findElementsBy($this->comment));
    }
}