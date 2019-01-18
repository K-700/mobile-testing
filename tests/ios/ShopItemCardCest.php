<?php

use Helper\Cart;
use Page\CartPage;
use Page\ShopItem\ShopItemCardPage;

class ShopItemCardCest
{
    public function _before(\IosTester $I)
    {
        $I->implicitWait(['ms' => 10000]);
//        $I->setUrl(['url' => 'http://test-site.com']);
//        $elem = $I->byCssSelector('.mobile-show');
//        $I->tap([[$elem->location()['x'], $elem->location()['y'] - 70]]);
        $I->setUrl(['url' => 'http://test-site.com/shop/nails/gel-laki']);
        sleep(5);
        $I->openRandomProductCard();
    }

    /**
     * Проверка присутствия основных элементов на карточке товара
     *
     * @param IosTester $I
     * @param ShopItemCardPage $shopItemCardPage
     */
//    public function shopItemCardTest(\IosTester $I, ShopItemCardPage $shopItemCardPage)
//    {
//        $I->hasImgWithSource($I->by($shopItemCardPage->photo));
//        $smallPhotos = $I->findElementsBy($shopItemCardPage->smallPhoto);
//        $I->assertGreaterThanOrEqual(1, count($smallPhotos));
//        foreach ($smallPhotos as $smallPhoto) {
//           $I->hasImgWithSource($smallPhoto);
//        }
//
//        $I->assertNotEmpty($I->by($shopItemCardPage->name)->text());
//        $I->assertGreaterThanOrEqual(1, count($I->findElementFromElementBy($I->by($shopItemCardPage->shareSection), $shopItemCardPage->shareIcon)));
//        $I->assertGreaterThanOrEqual(1, $I->grabIntFromString($I->by($shopItemCardPage->price)->text()));
//        $I->by($shopItemCardPage->quantity)->click();
//        $I->assertEquals(1, $I->grabIntFromString($I->by($shopItemCardPage->quantity)->value()));
//        $I->assertEquals("Добавить в корзину", $I->by($shopItemCardPage->addToCartButton)->text());
//        $shopItemCardPage->checkProperties();
//    }

    /**
     * Проверка блока описания и отзывов в карточке товара
     * Проверяется непустота блока описания и количество комментариев
     *
     * @param IosTester $I
     * @param ShopItemCardPage $shopItemCardPage
     * @throws \Facebook\WebDriver\Exception\TimeOutException
     */
//    public function commentsAndDescriptionTest(\IosTester $I, ShopItemCardPage $shopItemCardPage)
//    {
//        $I->waitForElementVisible($shopItemCardPage->descriptionContent, 5);
//        // Изначально показывается и блок описания, и блок комментариев
//        $I->assertNotEmpty($I->by($shopItemCardPage->descriptionContent)->text());
//        $I->assertNotEmpty($I->by($shopItemCardPage->commentsBlock->root)->text());
//
//        $I->amGoingTo('check number of comments');
//        $commentsButton = $I->by($shopItemCardPage->commentsButton);
//        $I->verticalSwipeToElement($commentsButton);
//        $I->by($shopItemCardPage->commentsButton)->click();
//        $shopItemCardPage->commentsBlock->openAllComments();
//        $I->assertEquals($I->grabIntFromString($I->by($shopItemCardPage->commentsCount)->text()), $shopItemCardPage->commentsBlock->getNumberOfComments());
//        $I->assertEmpty($I->by($shopItemCardPage->descriptionContent)->text());
//
//        $I->verticalSwipeToElement($I->by($shopItemCardPage->descriptionButton));
//        $I->by($shopItemCardPage->descriptionButton)->click();
//
//        $I->assertEmpty($I->by($shopItemCardPage->commentsBlock->root)->text());
//        $I->assertNotEmpty($I->by($shopItemCardPage->descriptionContent)->text());
//    }

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
                // такой случай имеет место если товар кончился на складе, тогда проверим действительно ли это так
                // для этого введем напрямую через поле ввода число $numberOfItemsToAdd и затем сравним с числом которое останется в поле ввода, они должны совпасть
                $numberOfItemsInStock = $shopItemCardPage->grabQuantity();
                $shopItemCardPage->inputQuantity($numberOfItemsToAdd);
                $shopItemCardPage->reduceQuantityByOne();
                $shopItemCardPage->increaseQuantityByOne();
                $I->assertEquals($shopItemCardPage->grabQuantity(), $numberOfItemsInStock);
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