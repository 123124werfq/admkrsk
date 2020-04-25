<?php

use backend\assets\GridAsset;
use backend\controllers\ReserveController;
use common\models\GridSetting;
use common\models\HrProfile;
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
            $username = $model->recordData['surname'] . " " . $model->recordData['name'] . " " . $model->recordData['parental_name'];
            if(empty($model->id_user))
                return $username ." <span class='badge badge-danger'>нет ЕСИА</span>";
            else
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
            $badge = ($model->updated_at == $model->created_at) ? " <sup>Новая</sup>" : "";
            return date("d-m-Y H:i", $model->updated_at ? $model->updated_at : $model->created_at).$badge;
        },
    ],
    'status:prop' => [
        'label' => 'Статус',
        'format' => 'html',
        'value' => function ($model) {
            return $model->getStatename(true);
        },
    ],
    'target:prop' => [
        'label' => 'Целевые должности',
        'format' => 'html',
        'value' => function ($model) {
            $rps = [];
            foreach ($model->reserved as $rp) {
                $rps[] = $rp->id_record_position;
            }
            $output = [];
            foreach ($model->positions as $pos) {
                if (in_array($pos->id_record_position, $rps)) {
                    $output[] = "<strike>" . $pos->positionName . "</strike>";
                } else {
                    $output[] = $pos->positionName;
                }
            }
            sort($output);
            return implode("<br>", $output);
        },
    ],
    'date-in-reserve:prop' => [
        'label' => 'Дата включения в кадровый резерв',
        'value' => function ($model) {
            return $model->reserve_date ? date("d-m-Y H:i", $model->reserve_date) : "не включен";
        },
    ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    HrProfile::class
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
        'rowOptions' => function ($model) {
            if ($model->isBusy()) {
                return ['class' => 'warning'];
            }
        },
        'columns' => array_merge(array_values($gridColumns), [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {editable} {ban} {archive} ',
                'buttons' => [
                    'archive' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-floppy-disk"]);
                        return Html::a($icon, $url, [
                            'disabled' => true,
                            'title' => 'Восстановить',
                            'aria-label' => 'Восстановить',
                            'data-pjax' => '0',
                        ]);
                    },
                    'editable' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                        return Html::a($icon, $url, [
                            'target' => '_blank',
                            'title' => 'Редактировать',
                            'aria-label' => 'Редактировать',
                            'data-pjax' => '0',
                        ]);
                    },
                    'ban' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-ban-circle"]);
                        return Html::a($icon, $url, [
                            'title' => 'Заблокировать',
                            'aria-label' => 'Заблокировать',
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