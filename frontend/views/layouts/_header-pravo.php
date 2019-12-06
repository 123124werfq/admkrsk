<?php

use yii\helpers\Html;
use yii\helpers\Url;

use common\models\User;

if(!Yii::$app->user->isGuest){
    $user = User::findOne(Yii::$app->user->id);
}

?>
<!-- панель слабовидящих -->
<div class="faceTuneBlock">
    <div class="blockWrapper">
        <div class="linkBlock">
            <div class="blockWrapper">
                <a href="?accessability=off" class="link link-accessability">Обычная версия сайта</a>
            </div>
        </div>
        <div class="settingsBlock" data-type="common">
            <div class="blockWrapper">
                <div class="groupBlock" data-type="font">
                    <strong class="blockTitle">Шрифт:</strong>
                    <div class="variantListBlock">
                        <ul class="variantList">
                            <li class="item item-active"><a href="?accessability=on&amp;size=2" data-key="acc-font" data-value="sans-serif">без засечек</a></li>
                            <li class="item"><a href="?accessability=on&amp;font=sans" data-key="acc-font" data-value="serif">с засечками</a></li>
                        </ul>
                    </div>
                </div>
                <div class="groupBlock" data-type="spacing">
                    <strong class="blockTitle">Интервал:</strong>
                    <div class="variantListBlock">
                        <ul class="variantList">
                            <li class="item item-active"><a href="?accessability=on&amp;spacing=1" data-key="acc-spacing" data-value="1">Обычный</a></li>
                            <li class="item"><a href="?accessability=on&amp;spacing=2" data-key="acc-spacing" data-value="2">Средний</a></li>
                            <li class="item"><a href="?accessability=on&amp;spacing=3" data-key="acc-spacing" data-value="3">Большой</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="settingsBlock" data-type="detailed">
            <div class="blockWrapper">
                <div class="groupBlock" data-type="size">
                    <strong class="blockTitle">Размер шрифта:</strong>
                    <div class="variantListBlock">
                        <ul class="variantList">
                            <li class="item item-active"><a href="?accessability=on&amp;size=1" data-key="acc-size" data-value="1" title="Обычный">1</a></li>
                            <li class="item"><a href="?accessability=on&amp;size=2" data-key="acc-size" data-value="2" title="Больше">2</a></li>
                            <li class="item"><a href="?accessability=on&amp;size=3" data-key="acc-size" data-value="3" title="Ещё больше">3</a></li>
                        </ul>
                    </div>
                </div>
                <div class="groupBlock" data-type="color">
                    <strong class="blockTitle">Цвет:</strong>
                    <div class="variantListBlock">
                        <ul class="variantList">
                            <li class="item"><a href="?accessability=on&amp;color=1" data-key="acc-color" data-value="1">A</a></li>
                            <li class="item"><a href="?accessability=on&amp;color=2" data-key="acc-color" data-value="2">A</a></li>
                            <li class="item"><a href="?accessability=on&amp;color=3" data-key="acc-color" data-value="3">A</a></li>
                            <li class="item item-active"><a href="?accessability=on&amp;color=4" data-key="acc-color" data-value="4">A</a></li>
                        </ul>
                    </div>
                </div>
                <div class="groupBlock" data-type="image">
                    <strong class="blockTitle">Изображения:</strong>
                    <div class="variantListBlock">
                        <ul class="variantList">
                            <li class="item item-active"><a href="?accessability=on&amp;color=1" data-key="acc-imagetype" data-value="0" title="Цветные">Цветные</a></li>
                            <li class="item"><a href="?accessability=on&amp;color=2" data-key="acc-imagetype" data-value="1" title="Монохромные">Монохромные</a></li>
                            <li class="item"><a href="?accessability=on&amp;color=2" data-key="acc-imagetype" data-value="-1" title="Без изображений">Нет</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /панель слабовидящих -->

<div class="page-shade"></div>
<section class="gosbar">
    <div class="container">
        <div class="gosbar__wrapper">
            <a href="#" class="gosbar_btn popup-block-link">
            </a>
            <div class="gosbar__right-block">
                <a href="#" class="gosbar_btn link-accessability">
                    <span class="material-icons gosbar-icon">visibility</span>
                    <span class="gosbar_btn-text">Версия для слабовидящих</span>
                </a>
            </div>
        </div>
    </div>
</section>
<header class="header">
    <div class="container">
        <div class="header-wrapper">
            <a href="/" class="header_logo">
                <img class="header_logo-img" src="<?= $bundle->baseUrl . '/img/logo.svg' ?>" alt="Администрация города Красноярск">
                <span class="header_logo-title-holder">
                    <span class="header_logo-title">Красноярск</span>
                    <span class="header_logo-subtitle">Официальный интернет-портал правовой информации города Красноярска</span>
                </span>
            </a>
            <h1 class="accessability-title">Официальный интернет-портал правовой информации города Красноярска</h1>
        </div>
    </div>
</header>