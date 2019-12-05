<?php
	use yii\helpers\Html;
	use yii\bootstrap\ActiveForm;

    $url = $data->getUrl();
?>
<div class="press-item">
    <div class="press-item_content">
        <h4 class="press_title"><a href="<?=$url?>"><?=Html::encode($data->title)?></a></h4>
        <ul class="press_info">
            <?php if (!empty($data->id_rub)){?>
            <li class="press_info-item press_info-item__place"><a href="?id_rub=<?=$data->id_rub?>"><?=$data->rub->getLineValue()?></a></li>
            <?php }?>
            <li class="press_info-item"><?= Yii::$app->formatter->asDatetime($date->date_publish) ?></li>
        </ul>
    </div>
    <?php if (!empty($data->id_media)){?>
    <a href="<?=$url?>" class="press_img-holder">
        <img class="press_img img-responsive" src="<?=$data->makeThumb(['w'=>768,'h'=>480])?>" alt="<?=Html::encode($data->title)?>">
    </a>
    <?php }?>
</div>