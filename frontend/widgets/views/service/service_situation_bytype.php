<?php use yii\helpers\Html;?>
<div class="smart-menu">
    <div class="container">
        <div class="smart-menu-tabs slide-hover tab-controls tab-controls__responsive">
            <div class="tab-controls-holder">
                <span class="slide-hover-line"></span>
                <!-- активному пункту добавлять класс tab-control__active -->
                <div class="smart-menu-tabs_item tab-control tab-control__active slide-hover-item" data-href="#fizical"><a class="smart-menu-tabs_control">Физическим лицам</a></div>
                <div class="smart-menu-tabs_item tab-control slide-hover-item" data-href="#business"><a class="smart-menu-tabs_control">Для бизнеса</a></div>
            </div>
        </div>
        <div class="smart-menu-content">
            <!-- активному пункту добавлять класс active -->
            <div id="fizical" class="tab-content active">
                <div class="situations">
                    <?php foreach ($situations as $key => $data) {?>
                    <div class="situations_item">
                        <div class="situations_img">
                            <img class="situations_img-picture" src="<?=(!empty($data->id_media))?$data->makeThumb(['w'=>64,'h'=>70]):''?>" alt="" width="64" height="70" alt="Название ситуациии">
                        </div>
                        <div class="situations_content">
                            <h3 class="situations_title"><?=Html::encode($data->name)?></h3>
                            <?php
                                $datas = [];
                                foreach ($data->childs as $ckey => $child)
                                    $datas[] = '<a href="'.$child->getUrl().'">'.$child->name.'</a>';

                                if (!empty($datas))
                                {
                            ?>
                            <div class="situations_another">
                                <?=implode('',$datas)?>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div id="business" class="tab-content">
                <div class="situations">
                <?php foreach ($firmsituations as $key => $data) {?>
                    <div class="situations_item">
                        <div class="situations_img">
                            <img class="situations_img-picture" src="<?=(!empty($data->id_media))?$data->makeThumb(['w'=>64,'h'=>70]):''?>" alt="" width="64" height="70" alt="Название ситуациии">
                        </div>
                        <div class="situations_content">
                            <h3 class="situations_title"><?=Html::encode($data->name)?></h3>
                            <?php
                                $datas = [];
                                foreach ($data->childs as $ckey => $child)
                                    $datas[] = '<a href="'.$child->getUrl().'">'.$child->name.'</a>';

                                if (!empty($datas))
                                {
                            ?>
                            <div class="situations_another">
                                <?=implode('',$datas)?>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                <?php }?>
                </div>        
            </div>
        </div>
    </div>
</div>