<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Page;
use common\models\Menu;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\MenuLink */
/* @var $form yii\widgets\ActiveForm */

if ($model->menu->type==$model->menu::TYPE_TABS)
    $pages = Page::find()->where('id_page IN (SELECT id_page FROM db_news)')->all();
else 
    $pages = Page::find()->all();
?>

<div class="ibox">
    <div class="ibox-content">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'state')->dropDownList([0=>'Не активен',1=>'Активен']) ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true])?>

    <div class="row">
        <?php if ($model->menu->type!=$model->menu::TYPE_TABS){?>
        <div class="col-sm-4">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        </div>
        <?php }?>
        <div class="col-sm-4">
            <?=$form->field($model, 'id_page')->widget(Select2::class, [
                'data' => ArrayHelper::map($pages, 'id_page', 'title'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Выберите раздел',
                ],
            ])?>
        </div>
        <div class="col-sm-4">
            <?=$form->field($model, 'id_menu_content')->widget(Select2::class, [
                'data' => ArrayHelper::map(Menu::find()->where('id_menu <>'.(int)$model->id_menu)->all(), 'id_menu', 'name'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Выберите подменю',
                ],
            ])?>
        </div>
    </div>

    <?php if (empty($model->menu->id_page)){?>
        <?= $form->field($model, 'content')->textarea(['class' => 'redactor form-control']) ?>

        <?php if ($model->menu->type==$model->menu::TYPE_TABS){?>
        <?= $form->field($model, 'template')->dropDownList($model->templates,['prompt'=>'Выберите шаблон']) ?>
        <?php }?>
    
        <?= common\components\multifile\MultiFileWidget::widget([
            'model'=>$model,
            'single'=>true,
            'relation'=>'media',
            //'records'=>[$value_model->media],
            'extensions'=>['jpg','jpeg','gif','png'],
            'grouptype'=>1,
            'showPreview'=>true
        ]);?>
    <?php }?>

    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>
