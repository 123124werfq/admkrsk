<?php

/* @var $this yii\web\View */
/* @var $model common\models\Poll */

$this->title = 'Редактирование голосования';
$this->params['breadcrumbs'][] = ['label' => 'Голосования', 'url' => ['reserve/contest']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-create">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form2', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
