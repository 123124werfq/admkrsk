<?php

use backend\assets\GridAsset;
use backend\controllers\GalleryController;
use common\models\Gallery;
use common\models\GridSetting;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\GallerySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Галереи';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);


if (Yii::$app->user->can('admin.gallery')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
}

$defaultColumns = [
    'id_gallery' => 'id_gallery:text:#',
    'name' => 'name',
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
    'created_by' => 'created_by',
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    Gallery::class
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

<div class="collection-index">
    <div class="ibox">
        <div class="ibox-content">

            <div style="margin-top: 10px">
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

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                    'data-grid' => GalleryController::grid,
                    'id' => 'grid',
                ]
            ]);
            ?>

        </div>
    </div>
</div>
