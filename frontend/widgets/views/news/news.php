<?php
    use yii\helpers\Html;
?>
<div class="press">
    <div class="container">
        <div class="custom-header">
            <div class="custom-header-left">
                <h2 class="chevron-title"><a href="#"><?=(!empty($blockVars['title']))?$blockVars['title']->value:'Пресс-центр'?><span class="material-icons">chevron_right</span></a></h2>
            </div>
            <div class="custom-header-right">
                <div class="smart-menu-tabs smart-menu-tabs__right slide-hover tab-controls tab-controls__invert tab-controls__responsive">
                    <div class="tab-controls-holder">
                        <span class="slide-hover-line"></span>
                        <?php foreach ($menu->links as $key => $link) {?>
                            <div class="smart-menu-tabs_item tab-control <?=$key==0?'tab-control__active':''?> slide-hover-item" data-href="#tabnews<?=$link->id_link?>"><a  class="smart-menu-tabs_control"><?=$link->label?></a></div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="press-content">
            <?php
            foreach ($menu->links as $key => $link) {
                $tab = $tabs[$link->id_link];
            ?>
            <div class="news tab-content <?=($key==0)?'active':''?>" id="tabnews<?=$link->id_link?>">
                <?php 
                if (!empty($link->template) && !empty($tab['news']))
                {
                    echo $this->render('@app/widgets/views/'.$link->template,['news'=>$tab['news'],'page'=>$link->page]);
                }
                elseif (!empty($tab['news'])){
                ?>
                <div class="news-list">
                    <?php if (!empty($tab['widenews'])){?>
                    <div class="news-item news-item__wide">
                        <div class="news-item_container">
                            <div class="news-item_picture">
                                <a href="<?=$tab['widenews']->getUrl()?>" class="news-item_img">
                                    <img class="img-responsive" src="<?=$tab['widenews']->makeThumb(['w'=>768,'h'=>384])?>" alt="<?=Html::encode($tab['widenews']->title)?>">
                                </a>
                            </div>
                            <div class="news-item_content">
                                <h3 class="news_title"><a href="<?=$tab['widenews']->getUrl()?>"><?=Html::encode($tab['widenews']->title)?></a></h3>
                                <p>
                                    <?=Html::encode($tab['widenews']->description)?>
                                </p>
                                <ul class="events_info">
                                    <?php if (!empty($tab['widenews']->id_rub)){?>
                                    <li class="events_info-item events_info-item__place"><a href="<?=$link->page->getUrl()?>?id_rub=<?=$tab['widenews']->id_rub?>"><?=$tab['widenews']->rub->getLineValue()?></a></li>
                                    <?php }?>
                                    <li class="events_info-item"><?=strftime('%d %B %Y, %M:%S',$tab['widenews']->date_publish)?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php }?>
                    <?php foreach ($tab['news'] as $nkey => $data){
                        echo $this->render('_news',['data'=>$data,'page'=>$link->page]);
                    }?>
                </div>
                <a class="press-more btn btn__block" href="<?=$link->getUrl()?>">Читать далее</a>
<?php           }
                else
                    if (!empty($tab['content']))
                       echo $tab['content'];
?>
            </div>
            <?php }?>
        </div>
    </div>
</div>