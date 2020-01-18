<?php

use backend\assets\GridAsset;
use backend\controllers\ServiceTargetController;
use common\models\GridSetting;
use common\models\ServiceTarget;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \backend\models\search\ServiceTargetSearch */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Цели';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

if ($archive) {
    $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
} else {
    $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
}

$defaultColumns = [
    'id_target' => 'id_target',
    'service.name' => [
        'attribute' => 'service.name',
        'label' => 'Название услуги',
    ],
    'name' => 'name',
    'reestr_number' => 'reestr_number',
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    ServiceTarget::class
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

<div class="service-target-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

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
            'data-grid' => ServiceTargetController::grid,
            'id' => 'grid',
        ]
    ]); ?>
</div>
