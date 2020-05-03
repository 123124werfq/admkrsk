<?php

use yii\helpers\Html;
use yii\helpers\Url;

use common\models\User;

if (!Yii::$app->user->isGuest)
    $user = Yii::$app->user->identity;
?>
<!-- панель слабовидящих -->
<div class="faceTuneBlock">
    <div class="blockWrapper">
        <div class="linkBlock">
            <div class="blockWrapper">
                <a href="?accessability=off" class="link link-accessability"><?=Yii::t('site', 'Обычная версия сайта')?></a>
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
            <a href="http://www.admkrsk.ru" class="gosbar_btn">
                <span class="gosbar-icon">
                    <svg class="gocbar-svgicon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><use xlink:href="#list_icon_svg"/><defs><path id="list_icon_svg" fill-rule="evenodd" d="M0 4h4V0H0v4zm6 12h4v-4H6v4zm-2 0H0v-4h4v4zm-4-6h4V6H0v4zm10 0H6V6h4v4zm2-10v4h4V0h-4zm-2 4H6V0h4v4zm2 6h4V6h-4v4zm4 6h-4v-4h4v4z"/></defs></svg>
                </span>
                <span class="gosbar_btn-text"><?=Yii::t('site', 'Сайт администрации города')?></span>
            </a>

            <div class="gosbar__right-block">
                <?php if(!strpos(Yii::$app->request->hostName, 'ants.') ){ ?>
                <form id="top-search" action="/search"><input class="header-search" name="q"></form>
                <a href="/search" class="gosbar_btn">
                    <span class="material-icons gosbar-icon" id="gosbar-search-go">search</span>
                    <span class="gosbar_btn-text" id="gosbar-search-go-btn"><?=Yii::t('site', 'Поиск по сайту')?></span>
                </a>
                <a href="/en" class="gosbar_btn"><span class="material-icons gosbar-icon">g_translate</span><span class="gosbar_btn-text">English</span></a>
                <a href="#" class="gosbar_btn link-accessability">
                    <span class="material-icons gosbar-icon">visibility</span>
                    <span class="gosbar_btn-text"><?=Yii::t('site', 'Версия для слабовидящих')?></span>
                </a>
                <?php } ?>
                <?php
                if (Yii::$app->language != 'en' && !strpos(Yii::$app->request->url, 'new-year2019') && !strpos(Yii::$app->request->hostInfo, 'newyear.')){
                  ?>
                <div class="dropdown dropdown-flex">
                    <span class="gosbar_btn dropdown-toggle">
                        <span class="material-icons gosbar-icon">account_circle</span>
                        <?php
                            if(isset($user)){
                                $ufirm = $user->getCurrentFirm();
                                $userTitle = $ufirm?($ufirm->shortname):$user->username;
                            }
                            else
                                $userTitle = 'Личный кабинет';

                            if (mb_strlen($userTitle) > 20)
                                $userTitle = mb_substr($userTitle, 0, 17) . '...';
                        ?>
                        <span class="gosbar_btn-text"><?=$userTitle?></span>
                        <span class="material-icons gosbar-icon gosbar-icon__right">arrow_drop_down</span>
                    </span>
                    <div class="dropdown-menu">
                        <?php if (!Yii::$app->user->isGuest): ?>
                            <a class="dropdown-menu_item" href="/personal">Личный&nbsp;кабинет</a>
                            <?php if(!strpos(Yii::$app->request->hostName, 'ants.') ){ ?>
                            <a class="dropdown-menu_item" href="/service">Мои&nbsp;запросы&nbsp;услуг</a>
                            <a class="dropdown-menu_item" href="/reception/list">Мои&nbsp;обращения</a>
                            <a class="dropdown-menu_item" href="/reception/request">Написать&nbsp;обращение</a>
                            <?php } ?>
                            <?= Html::a('Выйти', ['site/logout'], ['class' => 'dropdown-menu_item', 'data' => ['method' => 'post']]) ?>
                        <?php else: ?>
                            <a class="dropdown-menu_item" href="<?= Url::to(['site/login']) ?>">Войти</a>
                        <?php endif; ?>
                    </div>
                </div>
                    <?php
                } ?>
                <!-- Если есть уведомления добавить класс gosbar-icon__active -->
                <!--a href="#" class="gosbar_btn"><span class="material-icons gosbar-icon gosbar-icon__active">notifications</span>745</a-->
            </div>
        </div>
    </div>
</section>
<header class="header">
    <div class="container">
        <div class="header-wrapper">
            <a href="/" class="header_logo">
                <img class="header_logo-img" src="<?=$bundle->baseUrl.'/img/logo.svg' ?>" alt="<?=Yii::t('site', 'Администрация города Красноярск')?>">
                <span class="header_logo-title-holder">
                    <span class="header_logo-title"><?=Yii::t('site', 'Красноярск')?></span>
                    <span class="header_logo-subtitle"><?=Yii::t('site', 'Администрация города')?></span>
                </span>
            </a>
            <h1 class="accessability-title"><?=Yii::t('site', 'Администрация города Красноярск')?></h1>
            <div class="header_menu">
                <?php
                    /*if(Yii::$app->language == 'en')
                        echo \frontend\widgets\MenuWidget::widget(['template'=>'header_menu','alias'=>'en_header_menu']);
                    elseif (strpos(Yii::$app->request->url, 'new-year2019') || strpos(Yii::$app->request->hostInfo, 'newyear.'))
                        echo \frontend\widgets\MenuWidget::widget(['template'=>'header_menu','alias'=>'new_year_menu_header']);
                    else*/

                    if (!empty($header['menu']->value))
                        echo \frontend\widgets\MenuWidget::widget([
                            'template'=>'header_menu',
                            'id_menu'=>$header['menu']->value
                        ]);
                    else
                        echo \frontend\widgetdgets\MenuWidget::widget(['template'=>'header_menu','alias'=>'header_menu']);
                ?>
                <button class="header-menu_link header-menu_search search-toggle">
                    <span class="material-icons">search</span>
                    <span class="header-menu_btn-text"><?=Yii::t('site', 'Поиск по сайту')?></span>
                </button>
                <?php if(!strpos(Yii::$app->request->hostName, 'ants.') ){ ?>
                <button class="header-menu_link sitemap-toggle">
                    <span class="material-icons"><span class="sitemap-toggle_dots">more_horiz</span><span class="sitemap-toggle_menu">menu</span></span>
                    <span class="material-icons hidden">clear</span>
                </button>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="sitemap">
        <div class="container">
            <?php
            if (!empty($header['dropdown_menu']->value))
                echo \frontend\widgets\MenuWidget::widget([
                            'template'=>'subheader_menu',
                            'id_menu'=>$header['dropdown_menu']->value
                        ]);
            else
                echo \frontend\widgets\MenuWidget::widget(['alias'=>'subheader_menu','template'=>'subheader_menu']);
            /*if(Yii::$app->language == 'en')
                echo \frontend\widgets\MenuWidget::widget(['alias'=>'subheader_menu_en','template'=>'subheader_menu']);
            elseif (strpos(Yii::$app->request->url, 'new-year2019') || strpos(Yii::$app->request->hostInfo, 'newyear.'))
                echo \frontend\widgets\MenuWidget::widget(['alias'=>'subheader_menu_ny','template'=>'subheader_menu']);
            else*/

            ?>
        </div>
    </div>
</header>