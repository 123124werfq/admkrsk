<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2"><?=$page->title?></h1>

                <?php if(empty($appeals)) { ?>
                <p>Обращений/ запросов информации о деятельности администрации города Красноярска/ обжалований предоставления муниципальной услуги не поступало.</p>
                <?php } else { ?>
                    <ul>
                    <?php foreach ($appeals as $appeal){ ?>
                        <li>
                            <p><strong>№ <?=$appeal->number_internal?> от <?=date("d.m.Y", $appeal->created_at)?></strong><br>
                            Вид: обращение (заявление)<br>
                            Структурное подразделение: Администрация города Красноярска<br>
                            <?php if(isset($appeal->recordData['description'])) {?>
                            Краткое содержание: <?=$appeal->recordData['description']?><br>
                            <?php } ?>
                            Статус: <?=$appeal->statusName?><p>
                            <p>
                                Текст обращения: <br>
                                <em><?=$appeal->recordData['fulltext']?></em>
                            </p>
                        </li>
                    <?php }?>
                    </ul>
                <?php } ?>
                <div class="subscribe">
                    <div class="subscribe_left">
                        Поделиться:
                        <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
                    </div>
                    <div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> Распечатать</a></div>
                </div>
            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>