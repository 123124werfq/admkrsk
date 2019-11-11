<?php

/* @var $this yii\web\View */
/* @var $model common\models\Question */
/* @var $poll common\models\Poll */

$this->title = 'Создание ответа';
$this->params['breadcrumbs'][] = ['label' => $poll->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $poll->pageTitle, 'url' => ['view', 'id' => $poll->id_poll]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-create">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form_question', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
