<?php 
    if (empty($news))
        return '';
    
    $cover = array_shift($news);
?>
<div class="news-list">
    <div class="news-item news-item__wide news-item__wide-anons">
        <div class="news-item_holder">
            <h3 class="news_title"><a href="<?=$cover->getUrl()?>"><?=$cover->title?></a></h3>
            <?=$cover->content?>
            <ul class="events_info">
                <?php if (!empty($cover->id_rub)){?>
                <li class="events_info-item events_info-item__place"><a href="<?=$cover->getUrl()?>?id_rub=<?=$cover->id_rub?>"><?=$cover->rub->getLineValue()?></a></li>
                <?php }?>
                <li class="events_info-item"><?=strftime('%d %B %Y, %H:%M', $cover->date_publish)?></li>
            </ul>
        </div>
    </div>

    <?php foreach ($news as $key => $data)
        echo $this->render('_news',['data'=>$data,'page'=>$page]);
    ?>
</div>