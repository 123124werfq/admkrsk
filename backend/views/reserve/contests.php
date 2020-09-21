<?php

use backend\assets\GridAsset;
use backend\controllers\ReserveController;
use common\models\GridSetting;
use common\models\HrContest;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Голосования';
$this->params['breadcrumbs'][] = $this->title;
$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
GridAsset::register($this);

$defaultColumns = [
    'id_contest' => 'id_contest:integer:ID',
    'title' => 'title',
    'period:prop' => [
        'label' => 'Период',
        'format' => 'html',
        'value' => function ($model) {
            $info = '';

            if ($model->end < time()) $info = "<br>Время голосования истекло";

            return date("d-m-Y H:i", $model->begin) . " - " . date("d-m-Y H:i", $model->end) . $info;
        }
    ],
    'status:prop' => [
        'label' => 'Статус',
        'format' => 'html',
        'value' => function ($model) {
            return $model->getStatename(true);
        },
    ],
    'applicants:prop' => [
        'label' => 'Претенденты',
        'format' => 'html',
        'value' => function ($model) {
            $pretenders = [];
            foreach ($model->profiles as $profile) {
                $name = '';
                if(empty($profile->name))
                {
                    $data = $profile->getRecordData();
                    $name = $data['surname'].' '.$data['name'].' '.$data['parental_name'];
                }
                else
                    $name = $profile->name;

                $pretenders[] = "<a href='/reserve/editable?id={$profile->id_profile}' target='_blank'>" . $name . "</a>";
            }
            return implode('<br>', $pretenders);
        }
    ],
    'positions:prop' => [
        'label' => 'Должности',
        'format' => 'html',
        'value' => function ($model) {
            $positions = [];
            foreach ($model->profiles as $profile) {
                foreach ($profile->positions as $position)
                    $positions[] = $position->positionName;
            }
            $positions = array_unique($positions);
            sort($positions);
            return implode('<br>', $positions);
        }
    ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    HrContest::class
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
        //'filterModel' => $searchModel,
        'columns' => array_merge(array_values($gridColumns), [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{dynamic} {edit} {stop}',
                'buttons' => [
                    'stop' => function ($url, $model, $key) {
                        if ($model->state == HrContest::STATE_FINISHED) return '';

                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-stop"]);
                        return Html::a($icon, $url, [
                            'title' => 'Остановить',
                            'aria-label' => 'Остановить',
                            'data-pjax' => '0',
                        ]);
                    },
                    'edit' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                        return Html::a($icon, $url, [
                            'title' => 'Редактировать',
                            'aria-label' => 'Редактировать',
                            'data-pjax' => '0',
                        ]);
                    },
                    'dynamic' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-list-alt"]);
                        return Html::a($icon, $url, [
                            'title' => 'Результаты',
                            'aria-label' => 'Результаты',
                            'data-pjax' => '0',
                        ]);
                    },
                ],
                'contentOptions' => ['class' => 'button-column'],
            ],
        ]),
        'tableOptions' => [
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'data-grid' => ReserveController::gridContest,
            'id' => 'grid',
        ]
    ]); ?>


</div>