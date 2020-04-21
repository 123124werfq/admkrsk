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
                <div class="footer-phone">
                    <p class="footer-contacts_label"><?=Yii::t('site', 'Телефон/факс')?>:</p>
                    <p class="footer-contacts_text"><a href="tel:+73912119876">+7 (391) 211-98-76</a></p>
                </div>
                <div class="footer-address">
                    <p class="footer-contacts_label"><?=Yii::t('site', 'Адрес')?>:</p>
                    <p class="footer-contacts_text">660049, г. Красноярск, ул. Карла Маркса, 93</p>
                    <p class="footer-contacts_text">Электронная почта: adm@admkrsk.ru</p>
                </div>
                <?= frontend\widgets\MenuWidget::widget(['alias'=>'footer_social_menu','template'=>'footer_social_menu']); ?>
            </div>

            <div class="footer-contacts footer-contacts_phones">
                <div class="footer-phone">
                    <p class="footer-contacts_label">Отдел служебных писем (служебная корреспонденция):</p>
                    <p class="footer-contacts_text"><a href="tel:+73912261140">+7 (391) 226-11-40</a></p>
                    <p class="footer-contacts_text"><a href="tel:+73912261251">+7 (391) 226-12-51</a></p>
                </div>
                <div class="footer-phone">
                    <p class="footer-contacts_label">Обращения граждан:</p>
                    <p class="footer-contacts_text"><a href="tel:+73912261122">+7 (391) 226-11-22</a></p>
                </div>
                <div class="footer-phone">
                    <p class="footer-contacts_label">Телефон доверия:</p>
                    <p class="footer-contacts_text"><a href="tel:+73912261060">+7 (391) 226-10-60</a></p>
                </div>
            </div>
<?php } ?>
            <hr class="footer-hr">

            <div class="footer-end">
                <p class="copyright">&copy; 2001-<?=date("Y");?> <?=Yii::t('site', 'Администрация г. Красноярска')?></p>
            </div>
        </div>
    </div>
</footer>