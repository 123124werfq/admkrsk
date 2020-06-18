<?php
	$this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=987cf952-38fd-46ee-b595-02977f1247ac',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);

	$this->registerJsFile('/js/onmap.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);

	$script = <<< JS
		$(document).ready(function() {
	       showMap($id_collectom,'map$uniq_id');
		});
	JS;

	$this->registerJs($script, yii\web\View::POS_END);

?>

<div class="collection-map">
	<div id="map<?=$uniq_id?>" style="width: <?=$options['width']?>; height: <?=$options['height']?>"></div>
</div>