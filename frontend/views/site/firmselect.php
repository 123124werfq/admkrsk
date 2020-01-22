<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main">
    <div class="container">
        <ol class="breadcrumbs">
            <li class="breadcrumbs_item"><a href="/">Главная</a></li>
            <li class="breadcrumbs_item"><span>Вход на сайт</span></li>
        </ol>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <div class="pagetitle">
                    <h1>
                        Войти как
                    </h1>
                </div>

                <div class="content">
                    <ul>
                        <li><a href="<?=$returnUrl?>"><?=$fio?> (физическое лицо)</a></li>
                    <?php foreach($firms as $firm){?>
                        <li><a href="/asfirm?r=<?=urlencode($returnUrl)?>&f=<?=$firm['oid']?>"><?=$firm['fullName']?></a></li>
                    <?php }?>
                    </ul>
                </div>

            </div>
            <div class="col-third order-xs-0">

            </div>
        </div>
        <hr class="hr hr__md">
    </div>
</div>
