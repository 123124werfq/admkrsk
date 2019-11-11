<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Institution */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->id_institution], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_institution], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_institution], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
?>
<div class="institution-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_institution',
                    [
                        'attribute' => 'status',
                        'value' => $model->statusName,
                    ],
                    'description:ntext',
                    'comment',
                    'bus_id',
                    'version',
                    'modified_at:datetime',
                    'last_update:datetime',
                    'is_updating:boolean',
                    'fullname',
                    'shortname',
                    [
                        'attribute' => 'type',
                        'value' => $model->typeName,
                    ],
                    [
                        'attribute' => 'founder',
                        'format' => 'raw',
                        'value' => $model->founder ? implode('<br>', ArrayHelper::getColumn($model->founder, 'fullname')) : null,
                    ],
                    'ppo',
                    'ppo_oktmo_name',
                    'ppo_oktmo_code',
                    'ppo_okato_name',
                    'ppo_okato_code',
                    'okved_name',
                    'okved_code',
                    [
                        'attribute' => 'okved',
                        'format' => 'raw',
                        'value' => $model->okved ? implode('<br>', ArrayHelper::getColumn($model->okved, function ($array) {
                            return $array['code'] . ' ' . $array['name'];
                        })) : null,
                    ],
                    'okpo',
                    'okopf_name',
                    'okopf_code',
                    'okfs_name',
                    'okfs_code',
                    'oktmo_name',
                    'oktmo_code',
                    'okato_name',
                    'okato_code',
                    'address',
                    //'address_zip',
                    //'address_subject',
                    //'address_region',
                    //'address_locality',
                    //'address_street',
                    //'address_building',
                    //'address_latitude',
                    //'address_longitude',
                    'vgu_name',
                    'vgu_code',
                    'inn',
                    'kpp',
                    'ogrn',
                    'phone',
                    'email:email',
                    'website:url',
                    'manager',
                    //'manager_position',
                    //'manager_lastname',
                    //'manager_firstname',
                    //'manager_middlename',
                ],
            ]) ?>

        </div>
    </div>
</div>
