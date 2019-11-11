<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.collection')) {
    $this->params['button-block'][] = Html::a('Импортировать', ['import'], ['class' => 'btn btn-default']);
    $this->params['button-block'][] = Html::a('Добавить список', ['create'], ['class' => 'btn btn-success']);
}
?>

<div class="ibox">
    <div class="ibox-content">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
</div>
<div class="ibox">
    <div class="ibox-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                'id_collection',
                'name',
                'alias',
                [
                    'label'=>'Записей',
                    'value'=>function($model) {
                        return $model->getItems()->count();
                    }
                ],
                'is_dictionary',
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
</div>