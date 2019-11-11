<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Form Inputs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-input-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Form Input', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_input',
            'id_row',
            'id_type',
            'id_collection',
            'name',
            //'fieldname',
            //'visibleInput',
            //'visibleInputValue',
            //'values:ntext',
            //'size',
            //'options:ntext',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'deleted_at',
            //'deleted_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
