<?php

use common\models\Institution;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\InstitutionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="institution-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id_institution',
                    [
                        'attribute' => 'status',
                        'value' => function(Institution $model) {
                            return $model->statusName;
                        }
                    ],
                    //'description:ntext',
                    //'comment',
                    'shortname',
                    [
                        'attribute' => 'type',
                        'value' => function(Institution $model) {
                            return $model->typeName;
                        }
                    ],
                    //'bus_id',
                    'is_updating:boolean',
                    'last_update:datetime',
                    //'fullname',
                    //'okved_code',
                    //'okved_name',
                    //'ppo',
                    //'ppo_oktmo_name',
                    //'ppo_oktmo_code',
                    //'ppo_okato_name',
                    //'ppo_okato_code',
                    //'okpo',
                    //'okopf_name',
                    //'okopf_code',
                    //'okfs_name',
                    //'okfs_code',
                    //'oktmo_name',
                    //'oktmo_code',
                    //'okato_name',
                    //'okato_code',
                    //'address_zip',
                    //'address_subject',
                    //'address_region',
                    //'address_locality',
                    //'address_street',
                    //'address_building',
                    //'address_latitude',
                    //'address_longitude',
                    //'vgu_name',
                    //'vgu_code',
                    'inn',
                    //'kpp',
                    //'ogrn',
                    //'phone',
                    //'email:email',
                    //'website',
                    //'manager_position',
                    //'manager_firstname',
                    //'manager_middlename',
                    //'manager_lastname',
                    //'version',
                    //'modified_at',
                    //'created_at',
                    //'created_by',
                    //'updated_at',
                    //'updated_by',
                    //'deleted_at',
                    //'deleted_by',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>
</div>
