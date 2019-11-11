<?php
    use yii\helpers\Html;
?>
<div class="news-item">
    <h4 class="news_title"><a href="<?=$data->getUrl()?>"><?=Html::encode($data->title)?></a></h4>
    <ul class="events_info">
    	<?php if (!empty($data->id_rub)){?>
        <li class="events_info-item events_info-item__place"><a href="<?=$page->getUrl()?>?id_rub=<?=$data->id_rub?>"><?=$data->rub->getLineValue()?></a></li>
    	<?php }?>
        <li class="events_info-item"><?=strftime('%d %B %Y',$data->date_publish)?> <?=(!empty($data->date_unpublish)?' - '.strftime('%d %B %Y',$data->date_unpublish):'')?></li>
    </ul>
</div>