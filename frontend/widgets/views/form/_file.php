<?php use yii\helpers\Html;?>
<div class="fileupload_item dz-processing dz-image-preview dz-success dz-complete" data-filesize="<?=$media->size?>">
	<div class="fileupload_preview">
		<div class="fileupload_preview-type">
			<?php if ($media->isImage()){?>
				<img data-dz-thumbnail="" alt="" src="<?=$media->showThumb(['w'=>150,'h'=>150])?>"/>
			<?php }
				else
					echo Html::encode($media->extension);?>
		</div>
	</div>
	<div class="fileupload_item-content">
		<p class="fileupload_item-name" data-dz-name=""><?=Html::encode($media->name)?></p>
		<div class="fileupload_item-status">
			<span class="fileupload_item-size" data-dz-size=""><strong><?=round($media->size/1024/1024,2)?></strong> MB</span>
		</div>
	</div>
	<div class="fileupload_item-pagecount">
		<input class="form-control" type="number" min="1" step="1" name="FormDynamic[<?=$attribute?>][<?=$index?>][pagecount]" value="<?=!empty($media->pagecount)?$media->pagecount:''?>"/>
	</div>
	<a class="dz-remove" href="javascript:undefined;" data-dz-remove="">×</a>
	<input type="hidden" name="FormDynamic[<?=$attribute?>][<?=$index?>][id_media]" value="<?=$media->id_media?>">
</div>