<?php

use backend\assets\GridAsset;
use backend\controllers\SubscribeController;
use common\models\GridSetting;
use common\models\Vars;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Подписчики';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);


$defaultColumns = [
    'id' => 'id',
    'email' => 'email',
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
        'data-grid' => SubscribeController::grid,
        'id' => 'grid',
    ]
]); ?>