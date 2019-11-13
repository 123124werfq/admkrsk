<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\FiasAddrObj */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'areacode',
                    'autocode',
                    'citycode',
                    'code',
                    'enddate',
                    'formalname',
                    'ifnsfl',
                    'ifnsul',
                    'offname',
                    'okato',
                    'oktmo',
                    'placecode',
                    'plaincode',
                    'postalcode',
                    'regioncode',
                    'shortname',
                    'startdate',
                    'streetcode',
                    'terrifnsfl',
                    'terrifnsul',
                    'updatedate',
                    'ctarcode',
                    'extrcode',
                    'sextcode',
                    'plancode',
                    'cadnum',
                    'divtype',
                    'actstatus',
                    'aoguid',
                    'aoid',
                    'aolevel',
                    'centstatus',
                    'currstatus',
                    'livestatus',
                    'nextid',
                    'normdoc',
                    'operstatus',
                    'parentguid',
                    'previd',
                ],
            ]) ?>

        </div>
    </div>
</div>
