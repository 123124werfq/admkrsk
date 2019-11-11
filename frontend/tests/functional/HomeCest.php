<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

class HomeCest
{
    public function checkOpen(FunctionalTester $I)
    {
        $I->amOnPage(\Yii::$app->homeUrl);
        $I->see('Администрация города Красноярск');
        $I->see('Пресс-центр');
        $I->see('Опрос');
        $I->see('Городские проекты и события');
        $I->see('Гид по городу');
        $I->see('Полезные ссылки');
    }
}