<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

if (empty($dataProvider))
{
    $dataProvider = new ActiveDataProvider([
        'query' => \common\models\CollectionColumn::find()->where(['id_collection'=>$collection->id_collection]),
    ]);
}
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Столбцы';
$this->params['breadcrumbs'][] = ['label' => $collection->name, 'url' => ['collection-record/index','id'=>$collection->id_collection]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tabs-container">
    <ul class="nav nav-tabs" role="tablist">
        <li>
            <?=Html::a('Данные', ['collection-record/index', 'id' => $collection->id_collection], ['class' => 'nav-link'])?>
        </li>
        <li class="active">
            <?=Html::a('Колонки', ['collection-column/index', 'id' => $collection->id_collection], ['class' => 'nav-link'])?>
        </li>
        <li>
            <?=Html::a('Форма', ['form/view', 'id' => $collection->id_form], ['class' => 'nav-link'])?>
        </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active">
        <div class="panel-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id_column',
                    'name',
                    'alias',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions'=>['class'=>'button-column'],
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('', ['/collection-column/update', 'id' => $model->id_column],['class' => 'glyphicon glyphicon-pencil']);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('', ['/collection-column/delete', 'id' => $model->id_column],['class' => 'glyphicon glyphicon-trash','data' => [
                                'confirm' => 'Вы уверены что хотите удалить этот элемент?',
                                'method' => 'post',
                                ],]);
                            }
                        ],
                    ],
                ],
                'tableOptions'=>[
                    'emptyCell' => '',
                    'class'=>'table table-striped ids-style valign-middle table-hover ordered',
                    'data-order-url'=>'/collection-column/order'
                ]
            ]); ?>
        </div>
    </div>
</div>