<?php

use backend\assets\GridAsset;
use backend\controllers\FaqController;
use common\models\Faq;
use common\models\GridSetting;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

$defaultColumns = [
    'id_faq' => 'id_faq',
    'id_faq_categories' => [
        'attribute' => 'id_faq_categories',
        'value' => function (Faq $model) {
            return implode(', ', ArrayHelper::map($model->categories, 'id_faq_category', 'title'));
        },
    ],
    'status' => [
        'attribute' => 'status',
        'value' => function (Faq $model) {
            return $model->statusName;
        },
    ],
    'question' => 'question:html',
];

if (Yii::$app->user->can('admin.faq')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить вопрос', ['create'], ['class' => 'btn btn-success']);
}

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    Faq::class
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

<div class="faq-index">
    <div class="ibox">
        <div class="ibox-content">

            <div style="margin-bottom: 10px">
                <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
                <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
                <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
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
                        'contentOptions' => ['class' => 'button-column'],
                    ],
                ]),
                'tableOptions' => [
                    'emptyCell' => '',
                    'class' => 'table table-striped ids-style valign-middle table-hover',
                    'data-grid' => FaqController::grid,
                    'id' => 'grid',
                ]
            ]); ?>

        </div>
    </div>
</div>
