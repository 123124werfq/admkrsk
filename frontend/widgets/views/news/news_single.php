<?php
    use yii\helpers\Html;
?>
<div class="press <?=(!empty($blockVars['background']))?$blockVars['background']->value:''?>">
    <div class="container">
        <div class="custom-header">
            <div class="custom-header-left">
                <h2 class="chevron-title"><a href="<?=$page->getUrl()?>"><?=(!empty($blockVars['title']))?$blockVars['title']->value:'Пресс-центр'?><span class="material-icons">chevron_right</span></a></h2>
            </div>
        </div>
        <div class="press-content">
            <div class="news">
                <div class="news-list">
                    <div class="news-item news-item__wide">
                        <div class="news-item_container">
                        <?php if (!empty($wide)){?>
                            <div class="news-item_picture">
                                <a href="<?=$wide->getUrl()?>" class="news-item_img">
                                    <img class="img-responsive" src="<?=$wide->makeThumb(['w'=>768,'h'=>384])?>" alt="<?=Html::encode($wide->title)?>">
                                </a>
                            </div>
                            <div class="news-item_content">
                                <h3 class="news_title"><a href="<?=$wide->getUrl()?>"><?=Html::encode($wide->title)?></a></h3>
                                <p>
                                    <?=Html::encode($wide->description)?>
                                </p>
                                <ul class="events_info">
                                    <?php if (!empty($wide->id_rub)){?>
                                    <li class="events_info-item events_info-item__place"><a href="<?=$page->getUrl()?>?id_rub=<?=$wide->id_rub?>"><?=$wide->rub->getLineValue()?></a></li>
                                    <?php }?>
                                    <li class="events_info-item"><?=Yii::$app->formatter->asDatetime($wide->date_publish,'d MMMM yyyy HH:mm')?></li>
                                </ul>
                            </div>
                        <?php }?>
                        </div>
                    </div>
                    <?php foreach ($news as $nkey => $data)
                        echo $this->render('_news',[
                            'data'=>$data,
                            'page'=>$page]
                        );
                    ?>
                </div>
                <a class="press-more <?=(!empty($blockVars['button_color']))?$blockVars['button_color']->value:''?> btn btn__block" href="<?=$page->getUrl()?>"><?=(!empty($blockVars['button_text']))?$blockVars['button_text']->value:'Все новости'?></a>
            </div>
        </div>
    </div>
</div>