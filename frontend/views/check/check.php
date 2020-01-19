<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2">Информация о ходе рассмотрения обращения, запроса</h1>

                <div class="filter_layout custom-form">
                    <form action="">
                    <table cellpadding="0" cellspacing="0" style="min-width: 490px; max-width: 90%; width: auto;">
                        <tbody>
                        <tr valign="bottom">
                            <td class="field_filter" width="70%" nowrap="nowrap">
                                Регистрационный номер<br>
                                <input name="query" type="text" title="регистрационный номер, полученный Вами при подаче обращения/запроса информации/обжалования предоставления муниципальной услуги на Официальном сайте администрации города Красноярска, сайте Главы города Красноярска, либо номер, под которым заявка зарегистрирована в администрации города Красноярска" style="width:98%;min-width: 10em;">
                            </td>
                            <td>&nbsp;&nbsp;</td>
                            <td class="ibutton2" valign="top"><br><input type="submit" value="Поиск"></td>
                        </tr>
                        </tbody>
                    </table>
                    </form>
                </div>

                <?php if($result){ ?>
                    <p><?=$result?></p>
                <?php } ?>

                <?=$page->content?>

            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>