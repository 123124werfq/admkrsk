<?php
    $this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=987cf952-38fd-46ee-b595-02977f1247ac',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);

    $this->registerJsFile('/js/onmap.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
?>
<div class="main">
    <div class="container">
        <?= frontend\widgets\Breadcrumbs::widget(['page' => $page]) ?>
        <h1><?= $page->title ?></h1>
        <hr class="hr hr__large">
        <div class="content">
            <div class="row">
                <div class="col-half">
                    <div class="form-group">
                        <label class="form-label">Тип объекта</label>
                        <div class="custom-select">
                            <select id="map-controls">
                                <?php foreach ($collections as $key => $collection)
                                {
                                    echo '<option value="'.$collection->id_collection.'">'.$collection->name.'</option>';
                                }?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div id="map-container" class="map" style="height: 550px;"></div>
        </div>
    </div>
</div>