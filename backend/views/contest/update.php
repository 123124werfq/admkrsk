<?php

/* @var $this yii\web\View */
/* @var $model common\models\Poll */

$this->title = 'Редактирование голосования';
$this->params['breadcrumbs'][] = ['label' => 'Редактирование голосования', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="poll-create">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form2', [
                'model' => $model,
                'experts' => $experts,
                'comment' => $comment,
                'id' => $id
            ]) ?>

        </div>
    </div>
</div>
