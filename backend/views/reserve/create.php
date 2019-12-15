<?php

/* @var $this yii\web\View */
/* @var $model common\models\Poll */

$this->title = 'Создание конкурса';
$this->params['breadcrumbs'][] = ['label' => 'Создание конкурса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-create">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
