<?php

use backend\assets\GridAsset;
use backend\controllers\ReserveController;
use common\models\GridSetting;
use common\models\CstProfile;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel \backend\models\search\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$this->title = 'Анкеты, поданные для участия в конкурсах';
$this->params['breadcrumbs'][] = $this->title;
if($contestid>0)
    $this->params['button-block'][] = '<a class="btn btn-success" href="/collection-record/all-doc?id='.$contestid.'">Скачать архив</a>';

GridAsset::register($this);

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
    'readyness:prop' => [
        'label' => 'Готовность к проверке',
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

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    CstProfile::class
);

//var_dump($gridColumns);

//var_dump($visibleColumns);

?>


<div id="accordion">
    <h3 id="grid-setting">Настройки таблицы</h3>
    <div id="sortable">
        <?php foreach ($visibleColumns as $name => $isVisible): ?>
            <div class="ui-state-default">
                <input type="checkbox" <?= $isVisible ? 'checked' : null ?> />
                <span><?= $name ?></span></div>
        <?php endforeach; ?>
        <div class="ibox">
            <div style="
            padding-top: 5px;
            padding-left: 10px;">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'id' => 'sb']) ?>
            </div>
        </div>
    </div>
</div>

<div class="service-index">

    <div class="ibox">
        <div class="ibox-content">
    
        <!--
    <div class="ibox">
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
    </div>
    -->

    <select class="form-control" id="contestselect" onchange="filterContest()">
        <option value="0">Все</option>
    <?php 
        foreach($allContests as $contId => $contName){
    ?>
        <option value="<?=$contId?>" <?=$contId==$activecontest?'selected':''?>><?=$contName['name']?></option>
    <?php
        }
    ?>
    </select> 
    <script>
        let filterContest = function(){

        let newContestId = $('#contestselect').val();
        document.location.href = '/contest/profile?cont='+newContestId;
    } 
    </script> 

    
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
    //        'filterModel' => $searchModel,
            'columns' => array_merge(array_values($gridColumns), [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {editable} {ban} {status} ',
                    'buttons' => [
                        'view' => function ($url, $model, $key) {
                            $url = str_replace("=$key", "={$model['id_profile']}", $url);
                                        
                                return Html::a('', $url, [
                                    'class' => "glyphicon glyphicon-eye-open",
                                    'target' => '_blank',
                                    'title' => 'Редактировать',
                                    'aria-label' => 'Редактировать',
                                    'data-pjax' => '0',
                                ]);
                            },

                        'editable' => function ($url, $model, $key) {
                            //$url = str_replace("=$key", "={$model['id_record']}", $url);

                            return Html::a('', ['/collection-record/update', 'id' => $model['id_record']],['class' => 'glyphicon glyphicon-pencil update-record']);

                            /*$icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                            return Html::a($icon, '/collection-record/update?id='.$model['id_record'], [
                                'target' => '_blank',
                                'title' => 'Редактировать',
                                'aria-label' => 'Редактировать',
                                'data-pjax' => '0',
                            ]);*/
                        },
                        'status' => function ($url, $model, $key) {
                            $url = str_replace("=$key", "={$model['id_profile']}", $url);
                            
                            switch ($model['state']) {
                                case CstProfile::STATE_DRAFT:
                                    
                                    return Html::a('', $url, [
                                        'target' => '_blank',
                                        'title' => 'Принять',
                                        'aria-label' => 'Принять',
                                        'data-pjax' => '0',
                                        'class' => "glyphicon glyphicon-ok"
                                    ]);                                    
                                    
                                    break;
                                case CstProfile::STATE_ACCEPTED:
                                    return Html::a('', $url, [
                                        'target' => '_blank',
                                        'title' => 'Отклонить',
                                        'aria-label' => 'Отклонить',
                                        'data-pjax' => '0',
                                        'class' => "glyphicon glyphicon-remove"
                                    ]);                                                                        
                                    break;
                            }
                            
                        },                                     
                    ],
                    'contentOptions' => ['class' => 'button-column']
                ]
            ]),
            'tableOptions' => [
                'emptyCell' => '',
                'class' => 'table table-striped ids-style valign-middle table-hover',
                'data-grid' => ReserveController::gridProfile,
                'id' => 'grid',
            ]
        ]); ?>
        </div>
    </div>
</div>