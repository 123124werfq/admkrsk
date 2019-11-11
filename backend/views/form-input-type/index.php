<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FormInputTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы полей форм';
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>
<div class="form-input-type-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_type',
            'name',
            'regexp',
            //'type',
            'esia',
            'id_collection',
            //'values:ntext',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'deleted_at',
            //'deleted_by',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['class'=>'button-column']
            ],
        ],
        'tableOptions'=>[
            'emptyCell '=>'',
            'class'=>'table table-striped ids-style valign-middle table-hover'
        ]
    ]); ?>


</div>
