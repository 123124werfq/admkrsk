<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Form Elements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-element-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Form Element', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_element',
            'id_form',
            'id_input',
            'id_form_content',
            'type',
            //'content:ntext',
            //'ord',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'deleted_at',
            //'deleted_by',
            //'id_row',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
