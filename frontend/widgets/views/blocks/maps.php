<?php
    $this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=987cf952-38fd-46ee-b595-02977f1247ac',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);

    $this->registerJsFile('/js/onmap.js',['position'=>\yii\web\View::POS_END]);
?>
<div class="row">
    <div class="col-half">
        <div class="form-group">
            <label class="form-label">Тип объекта</label>
            <div class="custom-select">
                <select id="map-controls">
                    <?php foreach ($collections as $key => $collection)
                    {
                        # code...
                    }?>
                </select>
            </div>
        </div>
    </div>
</div>

<div id="map-container" class="map" style="height: 550px;">