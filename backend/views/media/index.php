<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Media */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Файлы';
$this->params['breadcrumbs'][] = $this->title;
$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id_media',
            'name',
            'size:size',
            //'height',
            //'duration',
            //'mime',
            //'extension',
            //'ord',
            'created_at:date:Создано',
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
