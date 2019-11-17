<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div>
            <h1>Программа праздника</h1>
            <div class="header-controls">
                <form>
                    <div class="btn-group">
                        <div class="btn-group_item">
                            <div class="datepicker-holder">
                                <input type="text" class="form-control form-control_datepicker mb-sm-all-0 datepicker-ajax" placeholder="Период мероприятий">
                                <button class="form-control-reset material-icons" type="button">clear</button>
                            </div>
                        </div>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select>
                                    <option selected="selected">Район</option>
                                    <option value="0">Все районы</option>
                                    <option value="1">Железнодорожный район</option>
                                    <option value="2">Кировский район</option>
                                    <option value="3">Ленинский район</option>
                                    <option value="4">Октябрьский район</option>
                                    <option value="5">Свердловский район</option>
                                    <option value="6">Советский район</option>
                                </select>
                            </div>
                        </div>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select>
                                    <option selected="selected">Место</option>
                                    <option value="0">Все места</option>
                                    <option value="1">Отстров Татышев</option>
                                    <option value="2">Остров Отдыха</option>
                                    <option value="3">Театральная площадь</option>
                                    <option value="4">Проспект Мира</option>
                                </select>
                            </div>
                        </div>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select>
                                    <option selected="selected">Категория мероприятия</option>
                                    <option value="0">Любая категория</option>
                                    <option value="1">Концерт</option>
                                    <option value="2">Митинг</option>
                                    <option value="3">Прогулка</option>
                                    <option value="4">Спорт</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- <hr class="hr"> -->
            <div class="program-list">
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
            </div>
            <!-- на время загрузки аякса добавлять к load-more класс active для анимации -->
            <!--a href="#" class="load-more">
                <span class="load-more_label">Показать ещё</span>
                <span class="load-more_loader">
                    <span class="load-more_dot-1"></span>
                    <span class="load-more_dot-2"></span>
                    <span class="load-more_dot-3"></span>
                </span>
            </a-->
        </div>
    </div>
</div>