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
    'extensions'=>['jpg','jpeg','gif','png'],
    'grouptype'=>1,
    'showPreview'=>true
]);?>

<?= $form->field($model, 'size')->radioList(Media::getSize()) ?>

<?= $form->field($model, 'title')->textArea() ?>

<?= $form->field($model, 'lightbox')->checkBox() ?>

<hr>
<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>