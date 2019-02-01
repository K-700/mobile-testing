<?php
namespace Page\Ios;

use Helper\TestUser;

class SubscribePage
{
    /** @var \IosTester */
    protected $tester;

    public $root;

    public $input;

    public $button;

    public $successMessage;

    public function __construct(\IosTester $I)
    {
        $this->tester = $I;

        $this->root = ['using' => 'class name', 'value' => 'form-start'];
        $this->input = ['using' => 'xpath', 'value' => "//input[@name='email']"];
        $this->button = ['using' => 'xpath', 'value' => "//input[@type='submit']"];
        $this->successMessage = 'Вы успешно подписались на новости интернет-магазина imkosmetik.com.';
    }

    /**
     * @param TestUser $testUser
     */
    public function subscribe(TestUser $testUser)
    {
        $I = $this->tester;
        $root = $I->findBy($this->root);

        $I->findElementFromElementBy($root, $this->input)->value($testUser->mail);
        $I->findElementFromElementBy($root, $this->button)->click();
    }

    public function invalidSubscribe()
    {
        $I = $this->tester;
        $root = $I->findBy($this->root);

        $I->findElementFromElementBy($root, $this->input)->value('');
        $I->findElementFromElementBy($root, $this->button)->click();
    }
}