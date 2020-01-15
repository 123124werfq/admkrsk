<?php

use backend\assets\GridAsset;
use backend\controllers\FormController;
use common\models\GridSetting;
use yii\helpers\Html;
use yii\grid\GridView;
use \common\models\Form;
use \common\models\Box;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Формы';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

if (Yii::$app->user->can('admin.form')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
}

$defaultColumns = [
    'id_form' => 'id_form',
    'name' => [
        'attribute' => 'name',
        'format' => 'html',
        'value' => function ($model) {
            $output = $model->name;
            if (!empty($model->collection))
                $output .= '<br>' . Html::a('Открыть список', ['collection/view', 'id' => $model->id_collection]);
            return $output;
        }
    ],
    'id_service' => [
        'attribute' => 'id_service',
        'format' => 'html',
        'value' => function ($model) {
            $output = '';
            if (!empty($model->service))
                $output = Html::a($model->service->reestr_number, ['service/view', 'id' => $model->id_service]);
            return $output;
        }
    ],
    [
        'attribute' => 'id_box',
        'value' => function ($model) {
            return (!empty($model->box))?$model->box->name:'';
        },
        'filter'    => ArrayHelper::map(Box::find()->all(),'id_box','name'),
    ],
    'created_at' => [
        'attribute' => 'created_at',
        'format' => [
            'date',
            'php:Y-m-d'
        ],
        'filterInputOptions' => [
            'class' => 'datepicker form-control',
        ],
    ],
    'updated_at' => [
        'attribute' => 'updated_at',
        'format' => [
            'date',
            'php:Y-m-d'
        ],
        'filterInputOptions' => [
            'class' => 'datepicker form-control',
        ],
    ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    Form::class
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

<div class="ibox">
    <div class="ibox-content">
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => array_merge(array_values($gridColumns), [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} ' . ($archive ? '{undelete}' : '{delete}'),
                'buttons' => [
                    'undelete' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-floppy-disk"]);
                        return Html::a($icon, $url, [
                            'title' => 'Восстановить',
                            'aria-label' => 'Восстановить',
                            'data-pjax' => '0',
                        ]);
                    },
                ],
                'contentOptions' => ['class' => 'button-column']
            ],
        ]),
        'tableOptions' => [
            'emptyCell ' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'data-grid' => FormController::grid,
            'id' => 'grid',
        ]
    ]); ?>
    </div>
</div>
