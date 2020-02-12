<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?php if (!$model->isNewRecord && !empty($model->collection->type->is_faq)){?>
    <div class="text-right">
        <a class="btn btn-info btn-visible" href="/faq/create?id=<?=$model->id_record?>">Экспортировать в Вопрос-Ответ</a>
        <br/><br/>
    </div>
<?php }?>
<?=\frontend\widgets\FormsWidget::widget([
    'form'=>$collection->form,
    'collectionRecord'=>$model,
    'nocaptcha'=>true,
    'inputs'=>[
        'id_collection'=>$collection->id_collection,
        'id_record'=>$model->id_record,
    ]]
)?>

<?php ActiveForm::end(); ?>

<?php 
$script = <<< JS
$(".fileupload").each(function(){
    var id_input = $(this).data('input');
    var new_index = $(this).find('.fileupload_item').length+1;

    var uploader = $(this).find('.fileupload_dropzone').dropzone({
        addRemoveLinks: true,
        url: "/media/upload",
        dictRemoveFile: '×',
        dictCancelUpload: '×',
        resizeWidth: 1920,
        previewsContainer: ".fileupload_list",
        previewTemplate: '<div class="fileupload_item">\
                            <div class="fileupload_preview">\
                                <div class="fileupload_preview-type">\
                                    <img data-dz-thumbnail />\
                                </div>\
                            </div>\
                            <div class="fileupload_item-content">\
                                <p class="fileupload_item-name" data-dz-name></p>\
                                <div class="fileupload_item-status"><span class="fileupload_item-size" data-dz-size></span>\
                                    <div class="fileupload_item-progress">\
                                        <div class="fileupload_progress-bar" data-dz-uploadprogress></div>\
                                    </div><div class="fileupload_item-progress-value">100%</div>\
                                </div>\
                            </div>\
                        </div>',
        init: function(){
            this.on("success", function(file, response){
                response = JSON.parse(response);
                $(file.previewElement).append(
                    '<input type="hidden" name="FormDynamic[input'+id_input+']['+new_index+'][file_path]" value="'+response.file+'"/>'
                );
                $(file.previewElement).append(
                    '<input type="hidden" name="FormDynamic[input'+id_input+']['+new_index+'][filename]" value="'+response.filename+'"/>'
                );
                
                if ($(file.previewElement).find('.fileupload_preview-type img').attr('src')==undefined)
                    $(file.previewElement).find('.fileupload_preview-type').text(response.file.split('.').pop());

                new_index++;
            });
        }
    });
});
JS;
$this->registerJs($script, yii\web\View::POS_END);
?>