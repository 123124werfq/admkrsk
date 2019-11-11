<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Содержимое меню';
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['menu/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['menu/update','id'=>$model->id_menu]];

$this->params['button-block'][] = Html::a('Редактировать', ['menu/update','id'=>$model->id_menu], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Добавить', ['create','id_menu'=>$model->id_menu], ['class' => 'btn btn-success']);

?>
<div class="row">
    <div class="col-md-9">
        <div class="ibox">
            <div class="ibox-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id_link',
                    'id_media',
                    'label',
                    'page.title:text:Ссылка на раздел',
                    'url:url',
                    //'content:ntext',
                    //'state',
                    //'ord',
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
                    'class'=>'table table-striped ids-style valign-middle table-hover ordered',
                    'data-order-url'=>'/menu/order'
                ],
            ]); ?>
            </div>
        </div>
    </div>
</div>