<?php

use backend\assets\GridAsset;
use backend\controllers\NewsController;
use common\models\GridSetting;
use common\models\News;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = $page->title;
$this->params['breadcrumbs'][] = $this->title;

$this->render('/page/_head', ['model' => $page]);

$this->params['button-block'][] = Html::a('Экспорт XLS', ['', 'export' => 1, 'id_page' => $page->id_page], ['class' => 'btn btn-default']);
GridAsset::register($this);


if (Yii::$app->user->can('admin.news')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index', 'id_page' => $page->id_page], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'id_page' => $page->id_page, 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить новость', ['create', 'id_page' => Yii::$app->request->get('id_page', 0)], ['class' => 'btn btn-success']);
}

$defaultColumns = [
    'id_news' => 'id_news',
    'title' => 'title',
    'rub.lineValue' => [
        'attribute' => 'rub.lineValue',
        'label' => 'Рубрика',
        'format' => 'text',
    ],
    'date_publish' => [
        'attribute' => 'date_publish',
        'label' => 'Дата публикации',
        'format' => [
            'date',
            'php:Y-m-d'
        ],
        'filterInputOptions' => [
            'class' => 'datepicker form-control',
        ],
    ],
    'date_unpublish' => [
        'attribute' => 'date_unpublish',
        'label' => 'Снять с публикации',
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
        'label' => 'Отредактировано',
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
    News::class
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
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>
</div>

<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs" role="tablist">
            <li>
                <?= Html::a('Дочерние разделы', ['page/view', 'id' => $page->id_page], ['class' => 'nav-link']) ?>
            <li>
                <?= Html::a('Шаблон', ['page/layout', 'id' => $page->id_page], ['class' => 'nav-link']) ?>
            </li>
            <li class="active">
                <?= Html::a('Новости', ['news/index', 'id_page' => $page->id_page], ['class' => 'nav-link']) ?>
            </li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" id="tab-1" class="tab-pane active">
                <div class="panel-body">

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
                            'data-grid' => NewsController::grid,
                            'id' => 'grid',
                        ],
                        'formatter' => [
                            'class' => 'yii\i18n\Formatter',
                            'locale' => 'ru-RU',
                            'nullDisplay' => '',
                        ],

                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
