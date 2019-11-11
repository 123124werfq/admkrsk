<?php

use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin(['action' => ['workflow/in'], 'options' => ['enctype' => 'multipart/form-data', 'method' => 'post']]);
?>

<?= $form->field($model, 'rawtext')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'file[]')->fileInput(['multiple' => true]) ?>

    <button>Отправить</button>

<?php ActiveForm::end(); ?>