<?php

use backend\assets\GridAsset;
use backend\controllers\ReserveController;
use common\models\GridSetting;
use common\models\ServiceAppeal;
use common\models\ServiceAppealState;
use common\models\Service;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\search\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$this->title = '';
$this->params['breadcrumbs'][] = $this->title;

GridAsset::register($this);

/*
$defaultColumns = [
    'id_profile' => 'id_profile:integer:ID',
    'usr:prop' => [
        'label' => 'Пользователь',
        'format' => 'html',
        'value' => function ($model) {
            $pmod = CstProfile::findOne($model['id_profile']);
            $username = $pmod->user->getUsername();
            return "<a href='/user/view?id={$pmod->id_user}'>$username</a>";
        },
    ],
    'date_create:prop' => [
        'label' => 'Дата создания',
        'attribute' => 'created_at',
        'format' => 'html',
        'value' => function ($model) {
            return date("d-m-Y H:i", $model['created_at']);
        },
    ],
    'actual_date:prop' => [
        'label' => 'Дата актуальности',
        'attribute' => 'updated_at',
        'format' => 'html',
        'value' => function ($model) {
            $badge = ($model['updated_at'] == $model['created_at']) ? "<span class='badge badge-danger'>Новая</span><br>" : "";
            return $badge . " " . date("d-m-Y H:i", $model['updated_at'] ? $model['updated_at'] : $model['created_at']);
        },
    ],    
    'contest:prop' => [
        'label' => 'Конкурс',
        'format' => 'html',
        'value' => function ($model) {
            $pmod = CstProfile::findOne($model['id_profile']);
            return $pmod->getContestinfo()['name'];
        },
    ],    
    'status:prop' => [
        'label' => 'Статус',
        'attribute' => 'state',
        'format' => 'html',
        'value' => function ($model) {
            if(!empty($model['additional_status']))
                $extra = '<br>Доп. статус: '.$model['additional_status'];
            else
                $extra = '';
            $pmod = CstProfile::findOne($model['id_profile']);
            return $pmod->getStatename(true).$extra;
        },
    ],
    [
        'label' => 'Готовность',
        'format' => 'html',
        'value' => function ($model) {
            $model = CstProfile::findOne($model['id_profile']);
            $rr = $model->getRecord()->one();
            if($rr)
            {
                $record = $model->getRecord()->one()->getData(true);

                $readyness = !empty($record['ready']);

                $message = $readyness?'<span class="badge badge-primary">Готово к проверке</span>':'<span class="badge badge-danger">Не готово к проверке</span>';

                return $message;
            }
            else
                return '<span class="badge badge-secondary">Удалено</span>';
        },
    ],
    'comment:prop' => [
        'label' => 'Комментарий',
        'format' => 'html',
        'value' => function ($model) {
            $model = CstProfile::findOne($model['id_profile']);
            $message = empty($model->comment)?("<a href='/contest/view?id={$model->id_profile}'>Редактировать комментарий</a>"):(htmlspecialchars(strip_tags($model->comment))."<br><a href='/contest/view?id={$model->id_profile}''>Редактировать комментарий</a>");

            return $message;
        },
    ],
];
*/

$allStatuses = [];

for ($i=-1; $i < 20 ; $i++) { 

    if(ServiceAppealState::statusNameByCode($i) != 'Неизвестный статус')
        $allStatuses[$i] = ServiceAppealState::statusNameByCode($i);
};

$defaultColumns = [
    'id_appeal' => 'id_appeal:integer:ID',
    'date:prop' => [
        'label' => 'Дата ',
        'attribute' => 'resdate',
        'format' => 'html',
        'value' => function ($model) {
            return date("d-m-Y H:i", $model['resdate']);
        },
        'filter' =>
        DateRangePicker::widget([
            'name' => 'createTimeRange',
            'attribute' => 'datetime_range',
            'convertFormat' => true,
            //'startAttribute'=> date('Y-m-d h:i'),
            //'endAttribute'=>date('Y-m-d h:i'),
            'presetDropdown' => true,
            'pluginOptions' => [
                'timePicker' => false,
                'timePickerIncrement' => 1,
                'locale' => [
                    'format' => 'Y-m-d h:i:s'
                ]
            ]
        ])        
    ],
    'reestr_number' => [
        'label' => 'Услуга',
        'attribute' => 'reestr_number',
        'filter' => Html::activeDropDownList(
            $searchModel,
            'reestr_number',
            ArrayHelper::map(Service::find()->where("reestr_number <> ''")->orderBy('reestr_number')->all(), 'reestr_number', 'reestr_number'),
            ['class' => 'form-control', 'prompt' => 'Все']
        )        
    ],
    /*
    'target_name' => [ 
        'label' => 'Наименование услуги',
        'attribute' => 'target_name',
    ],
    */
    'resstate' => [
        'label' => 'Текущий статус',
        //'attribute' => 'resstate',        
        'value' => function ($model) {
            return ServiceAppealState::statusNameByCode($model['resstate']);

            $curState = explode("→", $model['state_history']);

            return ServiceAppealState::statusNameByCode(end($curState));
        },
        'filter' => Html::activeDropDownList(
            $searchModel,
            'resstate',
            $allStatuses,
            ['class' => 'form-control', 'prompt' => 'Все']
        )          
    ],
    'state_history'  => [ 
        'label' => 'История',
        'format' => 'raw',
        //'attribute' => 'state_history',
        'value' => function ($model) {
            $curState = explode("→", $model['state_history']);
            $output = [];

            foreach($curState as $st)
            {
                $output[] = "<acronym title='" .ServiceAppealState::statusNameByCode($st) . "'>$st</acronym>"; 
            }

            return $model['state_history'].implode("→", $output);
        }
    ],
    'number_internal' => [ 
        'label' => '',
        'attribute' => 'number_internal',
    ],
    'number_system'  => [ 
        'label' => '',
        'attribute' => 'number_system',
    ],
];

$customColumns = [];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    ServiceAppeal::class
);

?>


<div class="service-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            $curState = explode("→", $model['state_history']);
            if(in_array(end($curState),[3,4]) )
                return ['class' => 'success'];

            if(end($curState)==0 && strtotime($model['resdate'])<strftime("1 day ago") ){
                return ['class' => 'danger'];
            }
        },        
        'columns' => array_merge(array_values($gridColumns), [
            /*
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [                                    
                ],
                'contentOptions' => ['class' => 'button-column']
            ]
            */
        ]),
        'tableOptions' => [
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'data-grid' => ReserveController::gridProfile,
            'id' => 'grid',
        ]
    ]); ?>

</div>