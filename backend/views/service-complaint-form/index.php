<?php

use backend\assets\GridAsset;
use backend\controllers\ServiceComplaintFormController;
use common\models\GridSetting;
use common\models\ServiceComplaintForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel ServiceComplaintForm */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$this->title = 'Сзязь форм и услуг для обжалования';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

$defaultColumns = [
   'id_appeal' => 'id_appeal',
   'form.name' => [
       'attribute' => 'form.name',
       'label' => 'Форма',
       'format' => 'text',
   ],
   'firm.lineValue' => [
       'attribute' => 'firm.lineValue',
       'label' => 'Организация',
       'format' => 'text',
   ],
   'category.lineValue' => [
       'attribute' => 'firm.lineValue',
       'label' => 'Категория',
       'format' => 'text',
   ],
   'service.reestr_number' => [
       'attribute' => 'service.reestr_number',
       'label' => 'Услуга',
       'format' => 'text',
   ],
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    ServiceComplaintForm::class
);

$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);

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
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

        <div style="margin-top: 10px">
            <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
            <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
            <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
        </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => array_merge(array_values($gridColumns), [
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['class'=>'button-column']
            ],
        ]),
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'data-grid' => ServiceComplaintFormController::grid,
            'id' => 'grid',
        ],
    ]); ?>
    </div>
</div>