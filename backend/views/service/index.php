<?php

use backend\assets\GridAsset;
use backend\controllers\ServiceController;
use common\models\GridSetting;
use common\models\Service;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\ServiceRubric;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Муниципальные услуги';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

$defaultColumns = [
    'id_service' => 'id_service',
    'reestr_number' => [
        'attribute' => 'reestr_number',
        'format' => 'text',
    ],
    'name' => [
        'attribute' => 'name',
        'options' => [
            'width' => '40%',
        ]
    ],
    'id_rub' => [
        'attribute' => 'id_rub',
        'filter' => ArrayHelper::map(ServiceRubric::find()->joinWith('childs as childs')->where('childs.id_rub IS NULL')->orderBy('name ASC')->all(), 'id_rub', 'name'),
        'options' => [
            'width' => '200',
        ],
        'value' => function ($model) {
            return ($model->id_rub) ? $model->rubric->name : '';
        },
    ],
    'old' => [
        'attribute' => 'old',
        'format' => 'boolean',
    ],
    'online' => [
        'attribute' => 'online',
        'label' => "Форма",
        'value' => function ($model) {
            return ($model->online) ? 'Онлайн' : 'Оффлайн';
        },
    ],
    'form:prop' => [
        'label' => "Формы",
        'value' => function ($model) {
            return $model->getForms()->where(['state' => 1])->count();
        },
    ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    Service::class
);

if (Yii::$app->user->can('admin.service')) {
    if ($archive)
        $this->params['action-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    else
        $this->params['action-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);

    $this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
}
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

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="ibox">
    <div class="ibox-content">
    <div style="margin-top: 10px; text-align:right;">
    <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
    <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
    <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
</div>
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
                'emptyCell' => '',
                'class' => 'table table-striped ids-style valign-middle table-hover',
                'data-grid' => ServiceController::grid,
                'id' => 'grid',
            ]
        ]); ?>
    </div>
</div>