<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="main">
    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <p>
            Страница, которую вы ищете, не существует.
        </p>
        <p>
            Проверьте правильность написания URL-адреса или перейдите на домашнюю страницу
        </p>
        <br/>
        <br/>
        <br/>

    </div>
</div>