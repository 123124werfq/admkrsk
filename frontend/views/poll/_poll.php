<?php

/* @var $model common\models\Poll */
/* @var $archive boolean */

use yii\helpers\Url;
?>
<div class="pull-item">
    <a href="<?= Url::to(['/poll/view', 'id' => $model->id_poll]) ?>" class="pull-item_title"><?= $model->title ?></a>
    <div class="pull-item_desc"><?= $model->description ?></div>
    <?php if ($model->date_start && $model->date_end): ?>
        <div class="pull-item_date">
            с <?= Yii::$app->formatter->asDate($model->date_start, 'd MMMM yyyy') ?> по <?= Yii::$app->formatter->asDate($model->date_end, 'd MMMM yyyy') ?>
        </div>
    <?php endif; ?>
</div>
