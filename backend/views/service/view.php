<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->params['button-block'][] = Html::a('Добавить цель', ['service-target/create', 'id' => $model->id_service], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_service], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_service], ['class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены что хотите удалить эту услугу?',
        'method' => 'post',
    ],
]);
?>

<div class="row">
    <div class="col-lg-5">
        <div class="ibox">
            <div class="ibox-content">
                <h2>Цели</h2>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'id_target',
                        'name',
                        'reestr_number',
                        'id_form',
                         [
                             'class' => 'yii\grid\ActionColumn',
                            'contentOptions'=>['class'=>'button-column']
                        ],
                    ],
                    'tableOptions'=>[
                        'emptyCell' => '',
                        'class' => 'table table-striped ids-style valign-middle table-hover'
                    ]
                ]); ?>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id_service',
                        'id_rub',
                        'reestr_number',
                        'fullname',
                        'name',
                        'keywords:raw',
                        'addresses:raw',
                        'result:raw',
                        'client_type',
                        'client_category:raw',
                        'duration:raw',
                        'documents:raw',
                        'price:raw',
                        'appeal:raw',
                        'legal_grounds:raw',
                        'regulations:raw',
                        'regulations_link:raw',
                        'duration_order:raw',
                        'availability:raw',
                        'procedure_information:raw',
                        'max_duration_queue:raw',
                        'old',
                        'online',
                    ],
                    'options'=>[
                        'class'=>'table table-striped detail-view'
                    ]
                ]) ?>
            </div>
        </div>
    </div>
</div>
