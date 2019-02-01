<?php

use Helper\Cart;
use Page\Ios\CartPage;
use Page\Ios\ShopItem\ShopItemCardPage;

class ShopItemCardCest
{
    public function _before(\IosTester $I)
    {
        $I->setUrl(['url' => 'http://test-site.com/shop/nails/gel-laki']);
        sleep(5);
        $I->openRandomProductCard();
    }

    /**
     * Проверка присутствия основных элементов на карточке товара
     *
     * @param IosTester $I
     * @param ShopItemCardPage $shopItemCardPage
     * @group restartSession
     */
    public function shopItemCardTest(\IosTester $I, ShopItemCardPage $shopItemCardPage)
    {
        $I->hasImgWithSource($I->findBy($shopItemCardPage->photo));
        $smallPhotos = $I->findElementsBy($shopItemCardPage->smallPhoto);
        $I->assertGreaterThanOrEqual(1, count($smallPhotos));
        foreach ($smallPhotos as $smallPhoto) {
           $I->hasImgWithSource($smallPhoto);
        }

        $I->assertNotEmpty($I->findBy($shopItemCardPage->name)->text());
        $I->assertGreaterThanOrEqual(1, count($I->findElementFromElementBy($I->findBy($shopItemCardPage->shareSection), $shopItemCardPage->shareIcon)));
        $I->assertGreaterThanOrEqual(1, $I->grabIntFromString($I->findBy($shopItemCardPage->price)->text()));
        $I->findBy($shopItemCardPage->quantity)->click();
        $I->assertEquals(1, $I->grabIntFromString($I->findBy($shopItemCardPage->quantity)->value()));
        $I->assertEquals("Добавить в корзину", $I->findBy($shopItemCardPage->addToCartButton)->text());
        $shopItemCardPage->checkProperties();
    }

    /**
     * Проверка блока описания и отзывов в карточке товара
     * Проверяется непустота блока описания и количество комментариев
     *
     * @param IosTester $I
     * @param ShopItemCardPage $shopItemCardPage
     */
    public function commentsAndDescriptionTest(\IosTester $I, ShopItemCardPage $shopItemCardPage)
    {
        // Изначально показывается и блок описания, и блок комментариев
        $I->assertNotEmpty($I->findBy($shopItemCardPage->descriptionContent)->text());
        $I->assertNotEmpty($I->findBy($shopItemCardPage->commentsBlock->root)->text());

        $I->amGoingTo('check number of comments');
        $commentsButton = $I->findBy($shopItemCardPage->commentsButton);
        $I->verticalSwipeToElement($commentsButton);
        $I->findBy($shopItemCardPage->commentsButton)->click();
        $shopItemCardPage->commentsBlock->openAllComments();
        $I->assertEquals($I->grabIntFromString($I->findBy($shopItemCardPage->commentsCount)->text()), $shopItemCardPage->commentsBlock->getNumberOfComments());
        $I->assertEmpty($I->findBy($shopItemCardPage->descriptionContent)->text());

        $I->verticalSwipeToElement($I->findBy($shopItemCardPage->descriptionButton));
        $I->findBy($shopItemCardPage->descriptionButton)->click();

        $I->assertEmpty($I->findBy($shopItemCardPage->commentsBlock->root)->text());
        $I->assertNotEmpty($I->findBy($shopItemCardPage->descriptionContent)->text());
    }

    /**
     * Проверка добавления товара в корзину на странице карточки
     * Проверяются кнопки увеличения/уменьшения количества, а так же ввод количества непосредственно в поле количества
     *
     * @param IosTester $I
     * @param ShopItemCardPage $shopItemCardPage
     */
    public function addAndSubItemsTest(\IosTester $I, ShopItemCardPage $shopItemCardPage)
    {
        $fullCartPage = new CartPage($I, CartPage::CART, new Cart());

        // проверка кнопки увеличения количества товара
        $numberOfItemsToAdd = 5;
        $I->amGoingTo("add $numberOfItemsToAdd to quantity");
        for ($i = 0; $i < $numberOfItemsToAdd; $i++) {
            $oldQuantity = $shopItemCardPage->grabQuantity();
            $shopItemCardPage->increaseQuantityByOne();
            if ($shopItemCardPage->grabQuantity() < $oldQuantity + 1) {
                $I->pauseExecution();
                // такой случай имеет место если товар кончился на складе, тогда проверим действительно ли это так
                // для этого введем напрямую через поле ввода число $numberOfItemsToAdd и затем сравним с числом которое останется в поле ввода, они должны совпасть
                $numberOfItemsInStock = $shopItemCardPage->grabQuantity();
                $shopItemCardPage->inputQuantity($numberOfItemsToAdd);
                $I->pauseExecution();
                $shopItemCardPage->reduceQuantityByOne();
                $I->pauseExecution();
                $shopItemCardPage->increaseQuantityByOne();
                $I->pauseExecution();
                $I->assertEquals($shopItemCardPage->grabQuantity(), $numberOfItemsInStock);
                $numberOfItemsToAdd = $numberOfItemsInStock;
                break;
            }
        }

        // проверка кнопки уменьшения количества товара
        $numberOfItemsToSub = 3;
        $I->amGoingTo("sub $numberOfItemsToSub from quantity");
        for ($i = 0; $i < $numberOfItemsToSub; $i++) {
            if ($shopItemCardPage->grabQuantity() == 1) {
                break;
            }
            $oldQuantity = $shopItemCardPage->grabQuantity();
            $shopItemCardPage->reduceQuantityByOne();
            $I->assertEquals($shopItemCardPage->grabQuantity(), $oldQuantity - 1);
        }

        $shopItemCardPage->addItemsToCart($numberOfItemsToAdd, $fullCartPage->cart);
        $fullCartPage->checkItems();
    }
}