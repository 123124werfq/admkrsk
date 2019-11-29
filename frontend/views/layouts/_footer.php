<footer class="footer">
    <div class="container">
        <div class="float-row clearfix">
            <div class="footer-main">
                <a href="/" class="footer-logo"><img src="<?= $bundle->baseUrl . '/img/footer-logo.svg' ?>" alt="Администрация города Красноярск"></a>
                <div class="footer-title">
                    <h3 class="footer-title_header">Красноярск</h3>
                    <p class="footer-title_text">Администрация города</p>
                </div>
                <a href="#" class="btn btn__transparent">Обратная связь</a>
            </div>
            <?php
                if (strpos(Yii::$app->request->url, 'new-year2019') || strpos(Yii::$app->request->hostInfo, 'newyear.'))
                    {   }// nY-menu
                else
                    frontend\widgets\MenuWidget::widget(['alias'=>'footer_menu','template'=>'footer_menu']);
            ?>
            <hr class="footer-hr">

            <div class="footer-contacts">
                <div class="footer-phone">
                    <p class="footer-contacts_label">Телефон/факс:</p>
                    <p class="footer-contacts_text"><a href="tel:+73912119876">+7 (391) 211-98-76</a></p>
                </div>
                <div class="footer-address">
                    <p class="footer-contacts_label">Телефон/факс:</p>
                    <p class="footer-contacts_text">660049, г. Красноярск, ул. Карла Маркса, 93</p>
                </div>
                <?= frontend\widgets\MenuWidget::widget(['alias'=>'footer_social_menu','template'=>'footer_social_menu']); ?>
            </div>

            <hr class="footer-hr">

            <div class="footer-end">
                <p class="copyright">&copy; 2001-2018 Администрация г. Красноярска</p>
                <a class="devby" href="http://alente.ru" target="_blank">разработка сайта <img src="<?= $bundle->baseUrl . '/img/icons/alente.svg' ?>" alt="alente"> <strong>alente</strong></a>
            </div>
        </div>
    </div>
</footer>