<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Формы';
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>
<div class="form-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id_form',
            'name',
            'collection.name',
            'created_at:date',
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
