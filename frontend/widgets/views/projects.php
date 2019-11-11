<div class="events">
    <div class="container">
        <h2 class="chevron-title section-title"><a href="<?=$page->getUrl()?>">Городские проекты и события<span class="material-icons">chevron_right</span></a></h2>
        <div class="events-list">
            <!--div class="events-item events-item__wide">
                <div class="events_img-holder">
                    <img class="events_img img-responsive" src="https://placekitten.com/1200/600" alt="Название мероприятия">
                    <div class="events_content">
                        <ul class="events_info hidden-xs hidden-accessability">
                            <li class="events_info-item events_info-item__place"><a href="#">Событие</a></li>
                            <li class="events_info-item">20 декабря 2019 — 31 января 2018</li>
                        </ul>
                        <h3 class="events_title"><a href="#">Всемирная универсиада 2019</a></h3>
                        <p class="events_text">
                            Всемирные студенческие спортивные игры, вот уже более 50 лет является вторым по значимости комплексным международным мероприятием на мировой спортивной арене.
                        </p>
                        <ul class="events_info visible-xs">
                            <li class="events_info-item events_info-item__place"><a href="#">Событие</a></li>
                            <li class="events_info-item">20 декабря 2019 — 31 января 2018</li>
                        </ul>
                    </div>
                </div>
            </div-->
            <?php foreach ($projects as $key => $data) {?>
                <div class="events-item">
                    <a href="<?=$data->getUrl()?>" class="events_img-holder">
                        <img class="events_img img-responsive" src="<?=$data->makeThumb(['w'=>768,'h'=>384])?>" alt="<?=$data->name?>">
                    </a>
                    <h4 class="events_title"><a href="<?=$data->getUrl()?>"><?=$data->name?></a></h4>
                    <ul class="events_info">
                        <?php if (!empty($data->typeValue)){?>
                        <li class="events_info-item events_info-item__place"><a href="<?=$page->getUrl()?>?type=<?=$data->type?>"><?=$data->typeValue->getLineValue()?></a></li>
                        <?php }?>
                        <li class="events_info-item"><?=strftime('%d %B %Y',$data->date_begin)?> <?=(!empty($data->date_end))?' - '.strftime('%d %B %Y',$data->date_end):''?></li>
                    </ul>
                </div>
            <?php }?>
        </div>
        <a href="<?=$page->getUrl()?>" class="btn btn__primary btn__block">Все проекты и события</a>
    </div>
</div>