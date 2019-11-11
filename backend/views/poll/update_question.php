<?php

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = 'Редактировать вопрос: ' . $model->question;
$this->params['breadcrumbs'][] = ['label' => $model->poll->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->poll->pageTitle, 'url' => ['view', 'id' => $model->poll->id_poll]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="poll-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form_question', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
