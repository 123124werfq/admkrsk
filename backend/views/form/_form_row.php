<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use common\models\FormInputType;
use common\models\FormInput;

use common\models\Collection;
use common\models\CollectionColumn;
?>

<div class="form-input-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'formInput-form'
        ]
    ]); ?>

    <?php
        $data = $model->getOptionsData();

        echo '<div class="row-flex">';

        foreach ($data as $key => $option)
        {
            $option['class'] = 'form-control';

            echo '<div class="col">
                    <label class="control-label">'.$option['name'].'</label>';
                    echo Html::dropDownList("FormRow[options][$key]",$option['value'],$option['values'],$option);
            echo '</div>';
        }
        echo '</div>';
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
