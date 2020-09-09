<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;

/**
 * @var string $POST_relation_name
 * @var string $group
 * @var string $single
 * @var string $records
 * @var string $showPreview
 * @var integer $template
 */

$this->registerJsFile('/js/dropzone/dropzone.js', ['depends' => [JqueryAsset::className()], 'position' => View::POS_END]);
$this->registerJsFile('/js/fileuploader/dropzone_multiupload.js', ['depends' => [JqueryAsset::className()], 'position' => View::POS_END]);
$this->registerCssFile('/js/dropzone/dropzone.min.css');
$uniq_id = substr(md5(time() . rand(0, 9999)), 0, 10);
?>

<?php
if (!empty($attribute))
    echo Html::activeHiddenInput($model, $attribute)
?>
    <div id="uploader<?= $uniq_id ?>" class="dropzone">
        <div class="dz-message">Перетащите файлы сюда или нажмите на область</div>
    </div>
    <input type="hidden" name="multiupload_<?= $POST_relation_name ?>" value="1"/>
<?php
$records = json_encode($records);

if (!empty($extensions))
    $allowedExtensions = 'acceptedFiles: "' . implode(',', $extensions) . '",';
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
				showAuthor: $showAuthor,
				$allowedExtensions
				showPreview: $showPreview,
				tpl: $template
			}
		);
	});
JS;

$this->registerJs($script, View::POS_END);
?>