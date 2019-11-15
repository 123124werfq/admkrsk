<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->id], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Действия', ['action', 'id' => $model->id], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
?>
<div class="user-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'id_esia_user',
                    'id_ad_user',
                    [
                        'attribute' => 'status',
                        'value' => $model->statusName,
                    ],
                    [
                        'attribute' => 'roles',
                        'format' => 'raw',
                        'value' => function () use ($model) {
                            return implode('<br>', ArrayHelper::map(Yii::$app->authManager->getRolesByUser($model->id), 'name', 'description'));
                        },
                        'filter' => false,
                    ],
                    'fullname',
                    'username',
                    'email:email',
//                    'auth_key',
//                    'password_hash',
//                    'password_reset_token',
//                    'created_at',
//                    'updated_at',
//                    'verification_token',
                ],
            ]) ?>

        </div>
    </div>

    <?php
        if(!empty($model->id_esia_user)){
    ?>

            <div class="ibox">
                <div class="ibox-content">

                    <?= DetailView::widget([
                        'model' => $model->getEsiainfo()->one(),
                        'attributes' => [
                            [
                                'attribute' => 'trusted',
                                'format' => 'raw',
                                'value' => function () use ($model) {
                                    $eu = $model->getEsiainfo()->one();
                                    return ($eu->trusted)?'Да':'Нет';
                                },
                                'filter' => false,
                            ],
                            'first_name',
                            'middle_name',
                            'last_name',
                            'email:email',
                            'mobile',
                            'home_phone',
                            'birthdate',
                            'snils',
                            'inn',
                            'birthplace',
                            'living_addr',
                            'register_addr'
                        ],
                    ]) ?>

                </div>
            </div>

    <?php
        }
    ?>

    <?php
    if(!empty($model->id_ad_user)){
        ?>

            <div class="ibox">
                <div class="ibox-content">

                    <?= DetailView::widget([
                        'model' => $model->getAdinfo()->one(),
                        'attributes' => [
                            'name',
                            'displayname',
                            'guid',
                            'sid',
                            'sn',
                            'sam_acc_type',
                            'sam_acc_name',
                            'city',
                            'company',
                            'department',
                            'description',
                            'title',
                            'principal',
                            'email:email',
                            'phone',
                            'office_name',
                            'office',
                            'homepage',
                            'fax',
                        ],
                    ]) ?>

                </div>
            </div>

        <?php
    }
    ?>

</div>
