<?php foreach ($program as $date => $day) {?>
<div class="program">
    <h2 class="program_date"><?=strftime('%e %B (%A)',(int)$date) ?></h2>
    <!--h3 class="program_event-title">«Город для детей»</h3-->
    <?php foreach ($day as $key => $event) {?>
    <div class="program-event">
        <div class="program_row">
            <div class="program_col-main">
                <h4 class="program_label"><?=$event['name']?></h4>
            </div>
            <div class="program_col-area">
                <span class="area">​<?=$event['place']?></span>
            </div>
            <div class="program_col-time">
                <?=$event['time']?>
            </div>
            <div class="program_col-main order-xs-1">
                <p class="program_desc hidden" id="event-1-1">
                    <?=$event['description']?>
                </p>
                <a href="#" class="program_more js-show" data-target="#event-1-1">Читать подробнее</a>
            </div>
            <!--div class="program_col-main order-xs-0">
                <div class="program_tags">
                    <ul class="tags">
                        <li class="tags-item"><a href="#" class="tags-item_link">Мэр</a></li>
                        <li class="tags-item"><a href="#" class="tags-item_link">Инспекция</a></li>
                        <li class="tags-item"><a href="#" class="tags-item_link">Жизнь города</a></li>
                    </ul>
                </div>
            </div-->
        </div>
    </div>
    <?php }?>
</div>
<?php }?>