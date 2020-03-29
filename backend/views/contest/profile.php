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

$this->title = 'Анкеты кандидатов в кадровый резерв';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

$defaultColumns = [
    'id_profile' => 'id_profile:integer:ID',
    'usr:prop' => [
        'label' => 'Пользователь',
        'format' => 'html',
        'value' => function ($model) {
            $username = $model->user->getUsername();
            return "<a href='/user/view?id={$model->id_user}'>$username</a>";
        },
    ],
    'date_create:prop' => [
        'label' => 'Дата создания',
        'format' => 'html',
        'value' => function ($model) {
            return date("d-m-Y H:i", $model->created_at);
        },
    ],
    'actual_date:prop' => [
        'label' => 'Дата актуальности',
        'format' => 'html',
        'value' => function ($model) {
            $badge = ($model->updated_at == $model->created_at) ? "<span class='badge badge-danger'>Новая</span><br>" : "";
            return $badge . " " . date("d-m-Y H:i", $model->updated_at ? $model->updated_at : $model->created_at);
        },
    ],    
    'contest:prop' => [
        'label' => 'Конкурс',
        'format' => 'html',
        'value' => function ($model) {
            return $model->getContestinfo()['name'];
        },
    ],    
    'status:prop' => [
        'label' => 'Статус',
        'format' => 'html',
        'value' => function ($model) {
            return $model->getStatename(true);
        },
    ],
    [
        'label' => 'Готовность',
        'format' => 'html',
        'value' => function ($model) {
            $rr = $model->getRecord()->one();
            if($rr)
            {
                $record = $model->getRecord()->one()->getData(true);

                $readyness = !empty($record['ready']);

                $message = $readyness?'<span class="badge badge-primary">Готово к проверке</span>':'<span class="badge badge-danger">Не готово к проверке</span>';

                return $message;
            }
        },
    ],
    'comment:prop' => [
        'label' => 'Комментарий',
        'format' => 'html',
        'value' => function ($model) {
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
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'rowOptions' => function ($model) 
        {
            $rr = $model->getRecord()->one();
            if($rr)
            {            
                $record = $model->getRecord()->one()->getData(true);

                if (!empty($record['ready'])) {
                    return ['class' => 'success'];
                }
            }
        },
        'columns' => array_merge(array_values($gridColumns), [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {editable} {ban} {status} ',
                'buttons' => [
                    'editable' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                        return Html::a($icon, $url, [
                            'target' => '_blank',
                            'title' => 'Редактировать',
                            'aria-label' => 'Редактировать',
                            'data-pjax' => '0',
                        ]);
                    },
                    'status' => function ($url, $model, $key) {
                        switch ($model->state) {
                            case CstProfile::STATE_DRAFT:
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-ok"]);
                                $title = 'Принять';
                                break;
                            case CstProfile::STATE_ACCEPTED:
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-remove"]);
                                $title = 'Отклонить';
                                break;
                        }
                        return Html::a($icon, $url, [
                            'target' => '_blank',
                            'title' => $title,
                            'aria-label' => $title,
                            'data-pjax' => '0',
                        ]);
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