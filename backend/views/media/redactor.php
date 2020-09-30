<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use common\models\Media;

    $form = ActiveForm::begin();
?>

<?=common\components\multifile\MultiFileWidget::widget([
    'model'=>$model,
    'single'=>true,
    'showAuthor'=>true,
    'relation'=>'media',
    'extensions'=>Yii::$app->request->get('file')?[]:['jpg','jpeg','gif','png'],
    'grouptype'=>1,
    'showPreview'=>true
]);?>

<?= $form->field($model, 'title')->textArea() ?>

<?php if (!Yii::$app->request->get('file')){?>
<?= $form->field($model, 'size')->radioList(Media::getSize()) ?>

<?= $form->field($model, 'lightbox')->checkBox() ?>
<?php }?>

<hr>
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>