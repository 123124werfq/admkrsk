<?php

use backend\assets\GridAsset;
use backend\controllers\VarsController;
use common\models\GridSetting;
use common\models\Vars;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Переменные';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

if (Yii::$app->user->can('admin.vars')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить переменную', ['create'],
        ['class' => 'btn btn-primary pull-right']);
}

$defaultColumns = [
    'id_var' => 'id_var',
    'name' => 'name',
    'alias' => 'alias',
    'content' => 'content:ntext',
];
list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    Vars::class
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

    <div style="margin-top: 10px">
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
    </div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => array_merge(array_values($gridColumns), [
        ['class' => 'yii\grid\ActionColumn',
            'template' => '<span class="btn btn-default">{update}</span> <span class="btn btn-default">{delete}</span>',
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
        ]
    ]),
    'tableOptions' => [
        'class' => 'panel table table-striped ids-style valign-middle',
        'data-grid' => VarsController::grid,
        'id' => 'grid',
    ]
]); ?>