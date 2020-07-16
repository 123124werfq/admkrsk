<?php

use backend\assets\GridAsset;
use backend\controllers\ReserveController;
use common\models\GridSetting;
use common\models\HrExpert;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Эксперты';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

$defaultColumns = [
    'id_expert' => 'id_expert:integer:ID',
    'fio:prop' => [
        'label' => 'ФИО',
        'value' => function ($model) {
            return $model->user->getUsername();
        }
    ],
    'date-create:prop' => [
        'label' => 'Дата добавления',
        'value' => function ($model) {
            return date("d-m-Y H:i", $model->created_at);
        },
        'filterInputOptions' => [
            'class' => 'datepicker form-control',
        ],
    ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    HrExpert::class
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
                'template' => '{dismiss}',
                'buttons' => [
                    'dismiss' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                        return Html::a($icon, $url, [
                            'title' => 'Исключить',
                            'aria-label' => 'Исключить',
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
            'data-grid' => ReserveController::gridList,
            'id' => 'grid',
        ]
    ]); ?>


</div>

<div id="user_group-users" class="row form-group">
    <div class="col-md-1">
        <h3>Пользователи</h3>
    </div>
    <div class="col-md-6">
        <div class="row">
            <?= Html::beginForm(['/contest/promote', 'id' => $model->id_expert], 'post', ['data-pjax' => '0', 'class' => 'form-inline']); ?>

            <div class="form-group col-md-9">
                <?= Select2::widget([
                    'model' => $expertForm,
                    'attribute' => 'id_user',
                    'data' => $expertForm->id_user ? ArrayHelper::map([User::findOne($expertForm->id_user)], 'id', function ($model) {
                        /* @var User $model */
                        return $model->getUsername();
                    }) : null,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'ajax' => [
                            'url' => Url::toRoute(['/contest/list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                    'options' => ['class' => 'col-md-9'],
                ]) ?>
            </div>

            <div class="col-md-3">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'style' => 'width: 100%; margin-top: 3px;']) ?>
            </div>

            <?= Html::endForm() ?>
        </div>
    </div>
</div>