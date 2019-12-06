<?php
    use yii\helpers\Html;
?>
<div class="news-item">
    <h4 class="news_title"><a href="<?=$data->getUrl()?>"><?=Html::encode($data->title)?></a></h4>
    <ul class="events_info">
    	<?php if (!empty($data->id_rub)){?>
        <li class="events_info-item events_info-item__place"><a href="<?=$page->getUrl()?>?id_rub=<?=$data->id_rub?>"><?=$data->rub->getLineValue()?></a></li>
    	<?php }?>
        <li class="events_info-item"><?=Yii::$app->formatter->asDatetime($data->date_publish,'d MMMM yyyy HH:mm')?> <?=(!empty($data->date_unpublish)?' - '.Yii::$app->formatter->asDatetime($data->date_unpublish,'d MMMM yyyy HH:mm'):'')?></li>
    </ul>
</div>