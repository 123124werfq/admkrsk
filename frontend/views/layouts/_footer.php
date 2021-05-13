<footer class="footer">
    <div class="container">
        <div class="float-row clearfix">
            <div class="footer-main">
                <a href="/" class="footer-logo"><img src="<?= $bundle->baseUrl . '/img/footer-logo.svg' ?>" alt="Администрация города Красноярск"></a>
                <div class="footer-title">
                    <h3 class="footer-title_header"><?=Yii::t('site', 'Красноярск')?></h3>
                    <p class="footer-title_text"><?=Yii::t('site', 'Администрация города')?></p>
                </div>
                <a href="https://www.admkrsk.ru/reception/Pages/request.aspx" class="btn btn__transparent"><?=Yii::t('site', 'Обратная связь')?></a>
            </div>
            <?php
                if (!empty($footer['menu']->value))
                    echo \frontend\widgets\MenuWidget::widget([
                            'template'=>'footer_menu',
                            'id_menu'=>$footer['menu']->value]);
                else
                    echo frontend\widgets\MenuWidget::widget(['alias'=>'footer_menu','template'=>'footer_menu']);
            ?>
            <hr class="footer-hr">
<?php if(Yii::$app->language == 'en'){?>
    <div class="footer-contacts">
                <div class="footer-phone">
                    <p class="footer-contacts_label"><?=Yii::t('site', 'Телефон/факс')?>:</p>
                    <p class="footer-contacts_text"><a href="tel:+73912119876">+7 (391) 226-11-35</a></p>
                </div>
                <div class="footer-address">
                    <p class="footer-contacts_label"><?=Yii::t('site', 'Адрес')?>:</p>
                    <p class="footer-contacts_text">660049, Krasnoyarsk, Karl Marks str., 93</p>
                </div>
                <?= frontend\widgets\MenuWidget::widget(['alias'=>'footer_social_menu','template'=>'footer_social_menu']); ?>
            </div>
<?php }else{ ?>
            <div class="footer-contacts">
                <?php if (!empty($footer['phone']->value)){?>
                <div class="footer-phone">
                    <p class="footer-contacts_label"><?=Yii::t('site', 'Телефон/факс')?>:</p>
                    <?=$footer['phone']->value?>
                </div>
                <?php }?>
                <?php if (!empty($footer['address']->value)){?>
                <div class="footer-address">
                    <p class="footer-contacts_label"><?=Yii::t('site', 'Адрес')?>:</p>
                    <?=$footer['address']->value?>

                </div>
                <?php }?>
                <?= frontend\widgets\MenuWidget::widget(['alias'=>'footer_social_menu','template'=>'footer_social_menu']); ?>
            </div>

            <div class="footer-contacts footer-contacts_phones">
                <?php if (!empty($footer['service_mail']->value)){?>
                <div class="footer-phone">
                    <p class="footer-contacts_label">Отдел служебных писем (служебная корреспонденция):</p>
                    <?=$footer['service_mail']->value?>
                </div>
                <?php }?>
                <?php if (!empty($footer['people_request']->value)){?>
                <div class="footer-phone">
                    <p class="footer-contacts_label">Обращения граждан:</p>
                    <?=$footer['people_request']->value?>
                </div>
                <?php }?>
                <?php if (!empty($footer['trust_phone']->value)){?>
                <div class="footer-phone">
                    <p class="footer-contacts_label">Телефон доверия:</p>
                    <?=$footer['trust_phone']->value?>
                </div>
                <?php }?>
            </div>
<?php } ?>
            <hr class="footer-hr">

            <div class="footer-end">
                <p class="copyright">&copy; 2001-<?=date("Y");?> <?=Yii::t('site', 'Администрация г. Красноярска')?></p>
            </div>
        </div>
    </div>
</footer>