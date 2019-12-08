<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Integration;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'События обмена с внешними системами';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-appeal-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_integration:text:#',
            [
                'format' => 'raw',
                'label' => 'Дата',
                'value' => function($model) {
                    return date('d-m-Y', $model->created_at) . "<br>" . date('H:i:s', $model->created_at);
                }
            ],
            [
                'format' => 'html',
                'label' => 'Система',
                'value' => function($model) {
                    switch ($model->system){
                        case Integration::SYSTEM_SMEV: return 'СМЭВ';
                        case Integration::SYSTEM_SED: return 'СЭД';
                        case Integration::SYSTEM_SUO: return 'СУО';
                    }
                }
            ],
            [
                'format' => 'html',
                'label' => 'Направление',
                'value' => function($model) {
                    switch ($model->direction){
                        case Integration::DIRECTION_OUTPUT: return 'Исходящее';
                        case Integration::DIRECTION_INPUT: return 'Входящее';
                    }
                }
            ],
            [
                'format' => 'html',
                //'label' => 'Статус',
                'value' => function($model) {
                    switch ($model->status){
                        case Integration::STATUS_OK: return '<span class="badge badge-pill badge-success">OK</span>';
                        case Integration::STATUS_ERROR: return '<span class="badge badge-pill badge-danger">Ошибка</span>';
                    }
                }
            ],
            [
                'format' => 'raw',
                'label' => 'Информация',
                'value' => function($model) {
                    $details = json_decode($model->data);
                    $output = "<em>{$model->description}</em><br>";
                    if($details)
                    {
                        if(isset($details->user)) $output.= "<a href='/user/view?id={$details->user}'>Пользователь</a><br>";
                        if(isset($details->appeal)) $output.= "<a href='/service-appeal/view?id={$details->appeal}'>Заявка</a><br>";
                        if(isset($details->target)) $output.= "<a href='/collection-record/index?id={$details->target}'>Список</a><br>";
                        if(isset($details->record)) $output.= "<a href='/service-appeal/index?id={$details->record}'>Заявка</a><br>";
                        return $output;
                    }

                }
            ],
            [
                'format' => 'raw',
                'label' => 'Информация',
                'value' =>    function($model) {
                    return "<button class='btn btn-info'>Перезапустить</button>";
                }
            ]
            /*
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['class'=>'button-column']
            ],
            */
        ],
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover'
        ],
    ]); ?>

</div>
