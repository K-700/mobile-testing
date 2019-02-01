<?php

use Page\Ios\Menu\CategoriesPage;

class CategoriesCest
{
    /**
     * @param IosTester $I
     * @param CategoriesPage $categoriesPage
     */
    public function categoriesTest(IosTester $I, CategoriesPage $categoriesPage)
    {
        $categoriesPage->openMenu();
        foreach ($I->findElementsBy($categoriesPage->categories) as $category) {
            $category->click();
            $I->assertEquals($category->text(), $I->findBy($categoriesPage->currentCategoryTitle)->text());
            $categoriesPage->checkNavsRecursively();
        }
    }
}