<?php

use backend\assets\GridAsset;
use backend\controllers\IntegrationsController;
use common\models\GridSetting;
use yii\grid\GridView;
use common\models\Integration;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$this->title = 'События обмена с внешними системами';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

$defaultColumns = [
    'id_integration' => 'id_integration:text:#',
    'date:prop' => [
        'format' => 'raw',
        'label' => 'Дата',
        'value' => function ($model) {
            return date('d-m-Y', $model->created_at) . "<br>" . date('H:i:s', $model->created_at);
        }
    ],
    'system:prop' => [
        'format' => 'html',
        'label' => 'Система',
        'value' => function ($model) {
            switch ($model->system) {
                case Integration::SYSTEM_SMEV:
                    return 'СМЭВ';
                case Integration::SYSTEM_SED:
                    return 'СЭД';
                case Integration::SYSTEM_SUO:
                    return 'СУО';
            }
        }
    ],
    'destination:prop' => [
        'format' => 'html',
        'label' => 'Направление',
        'value' => function ($model) {
            switch ($model->direction) {
                case Integration::DIRECTION_OUTPUT:
                    return 'Исходящее';
                case Integration::DIRECTION_INPUT:
                    return 'Входящее';
            }
        }
    ],
    'status:prop' => [
        'format' => 'html',
        'label' => 'Статус',
        'value' => function ($model) {
            switch ($model->status) {
                case Integration::STATUS_OK:
                    return '<span class="badge badge-pill badge-success">OK</span>';
                case Integration::STATUS_ERROR:
                    return '<span class="badge badge-pill badge-danger">Ошибка</span>';
            }
        }
    ],
    'information:prop' => [
        'format' => 'raw',
        'label' => 'Информация',
        'value' => function ($model) {
            $details = json_decode($model->data);
            $output = "<em>{$model->description}</em><br>";
            if ($details) {
                if (isset($details->user)) $output .= "<a href='/user/view?id={$details->user}'>Пользователь</a><br>";
                if (isset($details->appeal)) $output .= "<a href='/service-appeal/view?id={$details->appeal}'>Заявка</a><br>";
                return $output;
            }

        }
    ],
    'button:prop' => [
        'format' => 'raw',
        'label' => 'Действие',
        'value' => function ($model) {
            return "<button class='btn btn-info'>Перезапустить</button>";
        }
    ]
];

list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    Integration::class
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


<div class="service-appeal-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
        'tableOptions' => [
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'data-grid' => IntegrationsController::grid,
            'id' => 'grid',
        ],
    ]); ?>

</div>
