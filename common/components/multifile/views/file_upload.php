<?php
	use yii\helpers\Html;

	$this->registerJsFile('/js/dropzone/dropzone.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
	$this->registerJsFile('/js/fileuploader/dropzone_multiupload.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
	$this->registerCssFile('/js/dropzone/dropzone.min.css');
	$uniq_id = substr(md5(time().rand(0,9999)),0,10);
?>

<?php
	if (!empty($attribute))
		echo Html::activeHiddenInput($model,$attribute)
?>
<div id="uploader<?=$uniq_id?>" class="dropzone">
	<div class="dz-message">Перетащите файлы сюда или нажмите на область</div>
</div>
<input type="hidden" name="multiupload_<?=$POST_relation_name?>" value="1" />
<?php
$records = json_encode($records);

if (!empty($extensions))
	$allowedExtensions = 'acceptedFiles: "'.implode(',',$extensions).'",';
else 
	$allowedExtensions = '';

$script = <<< JS
	Dropzone.autoDiscover = false;

	$(document).ready(function(){
		$("#uploader$uniq_id").multiupload(
			{
				group: '$group',
				single: $single,
				relationname: '$POST_relation_name',
				records: $records,
				$allowedExtensions
				showPreview: $showPreview,
				tpl: $template
			}
		);
	});
JS;

$this->registerJs($script, yii\web\View::POS_END);
?>