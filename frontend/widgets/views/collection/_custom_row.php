<div class="collection-element">
	<?php
		$row['link'] = '/collection?id='.$id_record.'&id_page='.$id_page;
	?>

	<?=\common\components\helper\Helper::renderTwig($template,$row);?>
</div>