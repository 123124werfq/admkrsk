<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2"><?=$target->service->reestr_number?> <?=$target->service->name?></h1>

                <?php if($number) {?>
                <p>Зявка на оказание услуги отправлена. Номер регистрации <?=$number?>. Информацию о ходе оказания услуги вы можете получить в личном кабинете.</p>
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