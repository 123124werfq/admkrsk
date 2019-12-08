<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppeal */

$this->title = $model->id_appeal;
$this->params['breadcrumbs'][] = ['label' => 'Заявки по услугам', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if (!empty($model->target->service->template))
    $this->params['button-block'][] = Html::a('Скачать документ', ['doc','id'=>$model->id_appeal], ['class' => 'btn btn-success']);

\yii\web\YiiAsset::register($this);
?>
<div class="service-appeal-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_appeal',
            'created_at:date',
        ],
    ]) ?>

    <table class="table table-striped">
        <thead>
        <tr>
            <td>Поле</td>
            <td>Значение</td>
        </tr>
        </thead>
        <tbody>
    <?php foreach ($formFields as $alias => $field) {?>
        <tr>
            <td><?=$field['name']?></td>
            <td><?=$field['value']?></td>
        </tr>
    <?php } ?>
        </tbody>
    </table>
</div>
