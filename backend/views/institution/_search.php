<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\InstitutionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="institution-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_institution') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'bus_id') ?>

    <?php // echo $form->field($model, 'is_updating')->checkbox() ?>

    <?php // echo $form->field($model, 'last_update') ?>

    <?php // echo $form->field($model, 'fullname') ?>

    <?php // echo $form->field($model, 'shortname') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'okved_code') ?>

    <?php // echo $form->field($model, 'okved_name') ?>

    <?php // echo $form->field($model, 'ppo') ?>

    <?php // echo $form->field($model, 'ppo_oktmo_name') ?>

    <?php // echo $form->field($model, 'ppo_oktmo_code') ?>

    <?php // echo $form->field($model, 'ppo_okato_name') ?>

    <?php // echo $form->field($model, 'ppo_okato_code') ?>

    <?php // echo $form->field($model, 'okpo') ?>

    <?php // echo $form->field($model, 'okopf_name') ?>

    <?php // echo $form->field($model, 'okopf_code') ?>

    <?php // echo $form->field($model, 'okfs_name') ?>

    <?php // echo $form->field($model, 'okfs_code') ?>

    <?php // echo $form->field($model, 'oktmo_name') ?>

    <?php // echo $form->field($model, 'oktmo_code') ?>

    <?php // echo $form->field($model, 'okato_name') ?>

    <?php // echo $form->field($model, 'okato_code') ?>

    <?php // echo $form->field($model, 'address_zip') ?>

    <?php // echo $form->field($model, 'address_subject') ?>

    <?php // echo $form->field($model, 'address_region') ?>

    <?php // echo $form->field($model, 'address_locality') ?>

    <?php // echo $form->field($model, 'address_street') ?>

    <?php // echo $form->field($model, 'address_building') ?>

    <?php // echo $form->field($model, 'address_latitude') ?>

    <?php // echo $form->field($model, 'address_longitude') ?>

    <?php // echo $form->field($model, 'vgu_name') ?>

    <?php // echo $form->field($model, 'vgu_code') ?>

    <?php // echo $form->field($model, 'inn') ?>

    <?php // echo $form->field($model, 'kpp') ?>

    <?php // echo $form->field($model, 'ogrn') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'manager_position') ?>

    <?php // echo $form->field($model, 'manager_firstname') ?>

    <?php // echo $form->field($model, 'manager_middlename') ?>

    <?php // echo $form->field($model, 'manager_lastname') ?>

    <?php // echo $form->field($model, 'version') ?>

    <?php // echo $form->field($model, 'modified_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
