<?php

use backend\assets\GridAsset;
use backend\controllers\ReserveController;
use common\models\GridSetting;
use common\models\HrReserve;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$this->title = 'Кадровый резерв';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

$defaultColumns = [
    'id_profile' => 'id_profile:integer:ID',
    'reserver:prop' => [
        'label' => 'Резервист',
        'format' => 'html',
        'value' => function ($model) {
            $username = $model->profile->recordData['surname'] . " " . $model->profile->recordData['name'] . " " . $model->profile->recordData['parental_name'];
            return "<a href='/user/view?id={$model->profile->id_user}'>$username</a>";

        },
    ],
    'target:prop' => [
        'label' => 'Целевая должность',
        'format' => 'html',
        'value' => function ($model) {
            return $model->positionName;
        },
    ],
    'date-voice:prop' => [
        'label' => 'Дата голосования',
        'format' => 'html',
        'value' => function ($model) {
            return date("d-m-Y", $model->contest_date);
        },
    ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    HrReserve::class
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
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>">
            <button class="btn btn-primary">10</button>
        </a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>">
            <button class="btn btn-primary">20</button>
        </a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>">
            <button class="btn btn-primary">40</button>
        </a>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => array_merge(array_values($gridColumns), [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{unreserve}',
                'buttons' => [
                    'unreserve' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-ban-circle"]);
                        return Html::a($icon, $url, [
                            'title' => 'Убрать из резерва',
                            'aria-label' => 'Убрать из резерва',
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
            'data-grid' => ReserveController::gridExperts,
            'id' => 'grid',
        ]
    ]); ?>


</div>