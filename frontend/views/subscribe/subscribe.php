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
                Подписка на новости сайта администрации города Красноярска позволяет получать тексты новостей на свой
                адрес электронной почты не заходя на сайт.
            </div>
            <div class="content mb-2">
                Для подписки введите адрес своей электронной почты в поле формы подписки расположенной ниже.
            </div>
            <div class="content mb-3">
                После этого Вам будет отправлено письмо с адресом активации.
            </div>
            <?php $form = ActiveForm::begin([]); ?>

            <?= $form->field($subscribeForm, 'email',
                ['errorOptions' =>
                    ['class' => 'invalid-data']
                ]
            )->input('email') ?>

            <?= $form->field($subscribeForm, 'subscribeSections',
                ['errorOptions' =>
                    ['class' => 'invalid-data']
                ]
            )->checkboxList(News::getUniqueNews(),
                [
                    'separator' => '<br>'
                ]
            ) ?>

            <?= $form->field($subscribeForm, 'isAllSubscribe')->checkbox(
                [
                    'checked' => false,
                    'style' => [
                        'margin-bottom' => '20px'
                    ]
                ]
            ) ?>

            <div class="form-group">
                <?= Html::submitButton('Подписаться',
                    [
                        'class' => 'btn btn-success',
                        'style' => ['background' => '#8F1A1E', 'color' => '#ffffff !important', 'margin-bottom' => '20px']
                    ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>