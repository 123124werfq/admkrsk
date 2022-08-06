<div class="present">
    <div class="container">
        <div class="row">
            <div class="col-2-third">
                <h1><?=(!empty($blockVars['title']))?$blockVars['title']->value:'Муниципальные услуги'?></h1>
                <div class="present-container">
                    <div class="search search__present">
                        <form id="service_search" method="post">
                            <div class="search-holder">
                                <div class="search-group">
                                    <input id="service_search_input" class="form-control" type="text" placeholder="Введите название услуги">
                                </div>
                                <button class="search-btn btn btn__secondary">
                                    Найти
                                    <i class="material-icons btn-icon btn-icon__right">search</i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($menu)){?>
                    <div class="present-situations">
                        <?php foreach ($menu->activeLinks as $key => $link) {?>
                        <div class="situations_item situations_item__wide">
                            <div class="situations_img situations_img__bg">
                                <img class="situations_img-picture" src="<?=$link->makeThumb(['w'=>64,'h'=>70])?>" alt="" width="64" height="70" alt="">
                            </div>
                            <div class="situations_content">
                                <div class="situations_another">
                                    <a href="<?=$link->getUrl()?>"><?=$link->label?></a>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div class="col-third">
                <div class="statbar">
                    <div class="statbar_item">
                        <h5 class="statbar-item_title">Услуг предоставляемых в электронном виде</h5>
                        <div class="statbar-item_value"><?=$onlineCount?></div>
                    </div>
                    <div class="statbar_item">
                        <h5 class="statbar-item_title">Центры обслуживания ЕСИА</h5>
                        <div class="statbar-item_value">0</div>
                    </div>
                    <div class="statbar_item">
                        <h5 class="statbar-item_title">Количество голосов пользователей</h5>
                        <div class="statbar-item_value">0</div>
                    </div>
                    <div class="statbar_item">
                        <h5 class="statbar-item_title">Количество просмотров реестра открытых данных:</h5>
                        <div class="statbar-item_value">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>