<?php

use common\models\News;
use frontend\models\SubscribeForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/**
 * @var SubscribeForm $subscribeForm
 * @var View $this
 */
$this->registerCssFile('/css/subscribe/sub-style.css');
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-2-third">
                <ol class="breadcrumbs">
                    <li class="breadcrumbs_item">
                        <a href="/">Главная</a>
                    </li>
                    <li class="breadcrumbs_item">
                        <a href="<?= Url::to('/subscribe') ?>">Подписка</a>
                    </li>
                </ol>
            </div>
        </div>
        <div>
            <h1>Подписка</h1>
            <div class="content mb-2">
                Подписка оформлена. Перейдите по ссылке в письме, чтобы начать получать оповещения от сайта.
            </div>

        </div>
    </div>
</div>