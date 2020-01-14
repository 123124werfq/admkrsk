<?php

use backend\assets\GridAsset;
use backend\controllers\PageController;
use common\models\GridSetting;
use common\models\Page;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = $searchModel->breadcrumbsLabel;

if (!empty($partition ))
    $this->params['breadcrumbs'][] = ['label' => $partition->title, 'url' => ['partition', 'id' => $partition->id_page]];

$this->params['breadcrumbs'][] = $this->title;

GridAsset::register($this);
$defaultColumns = [
    'id_page' => 'id_page',
    'title' => 'title',
    'alias' => [
        'attribute' => 'alias',
        'format' => 'html',
        'value' => function ($model) {
            /**@var Page $model */
            return '<a target="_blank" href="' . $model->getUrl(true) . '">' . $model->getUrl() . '</a>';
        },
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
    'viewsYear:prop' => [
        'attribute' => 'viewsYear',
        'label' => 'За год',
        'format' => 'integer',

    ],
    'views:prop' => [
        'attribute' => 'views',
        'label' => 'Всего',
        'format' => 'integer',

    ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    Page::class
);

if (Yii::$app->user->can('admin.page')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']);
}
$this->params['button-block'][] = Html::a('Дерево', ['tree'], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Экспорт XLS', ['', 'export' => 1], ['class' => 'btn btn-default']);
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

<div class="page-index">
    <!--div class="ibox">
        <div class="ibox-content">
            <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div-->
    <div class="ibox">
        <div class="ibox-content">
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
                    'data-grid' => PageController::grid,
                    'id' => 'grid',
                ]
            ]); ?>

        </div>
    </div>
</div>
