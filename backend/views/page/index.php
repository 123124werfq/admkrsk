<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->can('admin.page')) {
    $this->params['button-block'][] = Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']);
}
$this->params['button-block'][] = Html::a('Дерево', ['tree'], ['class' => 'btn btn-default']);
?>
<div class="page-index">
    <div class="ibox">
        <div class="ibox-content">
            <?=$this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    'id_page',
                    [
                        'attribute' => 'title',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->title.'<br><a target="_blank" href="'.$model->getUrl(true).'">'.$model->getUrl().'</a>';
                        },
                    ],
                    //'parent.title:text:Родитель',
                    'created_at:date',
                    'updated_at:date',
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
</div>
