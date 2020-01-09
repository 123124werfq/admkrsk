<?php
    $this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2"><?=$service->reestr_number?> <?=$service->name?></h1>

                <?php if($number) {?>
                <p>Уважаемый(ая) <?=$fio?>, Ваше завка на получение услуги №<strong><?=$service->reestr_number?>-<?=$number?></strong> от <?=$date?>, направленное через виртуальную приемную, поступило в отдел по работе с обращениями граждан (многоканальный телефон +7 (391) 226-11-22). Спасибо за использование сайта!</p>


                <!--p>Зявка на оказание услуги отправлена. Номер регистрации <?=$number?>. Информацию о ходе оказания услуги вы можете получить в личном кабинете.</p-->
                <?php } else { ?>
                <p>Произошла ошибка при отправке</p>
                <?php } ?>

                <!--div class="subscribe">
                    <div class="subscribe_left">
                        Поделиться:
                        <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
                    </div>
                    <div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> Распечатать</a></div>
                </div-->
            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>