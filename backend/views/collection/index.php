<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\GridAsset;
use common\models\GridSetting;
use common\models\Box;
use common\models\Collection;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.collection')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Импортировать', ['import'], ['class' => 'btn btn-default']);
    $this->params['button-block'][] = Html::a('Добавить список', ['create'], ['class' => 'btn btn-success']);
}

$grid = GridSetting::findOne([
    'class' => 'collection-grid',
    'user_id' => Yii::$app->user->id,
]);

$customColumns = null;

if ($grid)
    $customColumns = json_decode($grid->settings, true);

GridAsset::register($this);
$defaultColumns = [
    'id_collection'=>'id_collection',
    'name'=>'name',
    'alias'=>'alias',
    'records'=>[
        'label'=>'Записей',
        'value'=>function($model) {
            return $model->getItems()->count();
        }
    ],
    'created_at'=>'created_at:date',
    'id_box'=>[
        'attribute' => 'id_box',
        'value' => function ($model) {
            return (!empty($model->box))?$model->box->name:'';
        },
        'filter' => ArrayHelper::map(Box::find()->all(),'id_box','name'),
    ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    Collection::class
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
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => array_merge(array_values($gridColumns), [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {copy} ' . ($archive ? '{undelete}' : '{delete}'),
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
            'tableOptions'=>[
                'emptyCell '=>'',
                'class'=>'table table-striped ids-style valign-middle table-hover'
            ]
        ]); ?>

    </div>
</div>