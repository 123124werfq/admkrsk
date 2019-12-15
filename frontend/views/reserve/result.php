<?php
/* @var common\models\Page $page */

/**
 * @param string $tag
 * @return array
 */

?>
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-2-third">
                    <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
                </div>
            </div>
            <div class="row">
                <div class="col-2-third order-xs-1">
                    <div class="content searchable">
                        <h1><?=$page->title?></h1>
                        <?php

                            echo $form->message_success;
                        ?>
                    </div>
                </div>
                <div class="col-third order-xs-0">
                    <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
                </div>
            </div>

            <hr class="hr hr__md"/>

            <div class="row">
                <div class="col-2-third">
                    <p class="text-help">
                        Дата публикации (изменения): <?=date('d.m.Y',$page->created_at)?> (<?=date('d.m.Y',$page->updated_at)?>)<br>
                        Просмотров за год (всего): <?=$page->viewsYear?> (<?=$page->views?>)
                    </p>
                    <div class="subscribe">
                        <div class="subscribe_left">
                            Поделиться:
                            <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
                        </div>
                        <div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> Распечатать</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?=frontend\widgets\AlertWidget::widget(['page'=>$page])?>