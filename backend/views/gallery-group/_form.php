<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Gallery;

/* @var $this yii\web\View */
/* @var $model common\models\GalleryGroup */
/* @var $form yii\widgets\ActiveForm */

$galleries = ArrayHelper::map(Gallery::find()->all(), 'id_gallery', 'name');
$records = $model->getRecords('galleries');
?>

<div class="ibox">
    <div class="ibox-content">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <br/>
    <h2>Добавить элементы</h2>

    <div id="list-records" class="multiinput sortable m-t">
        <?php foreach ($records as $key => $data) {?>
        <div class="row" data-row="<?=$key?>">
            <div class="col-md-11">
                <?=Html::hiddenInput("Gallery[galleries][$key][ord]",$data->ord,['id'=>'Gallery_ord_'.$key]);?>
                <?=Html::dropDownList("Gallery[galleries][$key][id_gallery]",$data->id_gallery,$galleries,['class'=>'form-control','id'=>'Gallery_id_gallery'.$key,'prompt'=>'Выберите галерею']);?>
            </div>
            <div class="col-md-1">
                <a class="close btn" href="#">&times;</a>
            </div>
        </div>
        <?php }?>
    </div>
    <a style="margin-left: 18px;" class="btn btn-default" onclick="return addInput('list-records')" href="#">Добавить еще</a>

    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

    </div>
</div>
