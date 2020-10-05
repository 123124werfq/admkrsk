<?php
    $this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1 content">
                <h1 class="h2"><?=$page?$page->title:'Найденные обращения и услуги'?></h1>

                <?php if(empty($appeals)) { ?>
                    <p>Запросов предоставления муниципальных услуг не поступало.</p>
                <?php } else { ?>
                    <div class="table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <th width="25%">Номер и дата запроса</th>
                            <th width="50%">Услуга</th>
                            <th width="25%">Состояние заявки</th>
                            <th>Оценка рассмотрения заявки на предоставление услуги</th>
                        </tr>  
                        </thead>           
                        <?php foreach ($appeals as $appeal){  ?>
                        <tr>
                            <td><?=$appeal->number_common?><!--<?=$appeal->target->reestr_number?>-<?=$appeal->id_appeal?>--><br><?=date("d.m.Y", $appeal->created_at)?></td>
                            <td>
                                <?=$appeal->target->reestr_number?><br>
                                <?=$appeal->target->name?><br><br>
                                Номер: <?=$appeal->number_system?><br>
                                Дата регистрации: <?=$appeal->statusDate?><br>
                            </td>
                            <td><?=$appeal->statusName?></td>
                            <td>
                                <?php 
                                    if($page){
                                ?>
                                <select>
                                    <option>5</option>
                                    <option>4</option>
                                    <option>3</option>
                                    <option>2</option>
                                    <option>1</option>
                                </select>
                                    <?php } ?>
                            </td>
                        </tr>
                        <?php }?>
                    </table>
                    </div>
                    <!--
                    <ul>
                        <?php foreach ($appeals as $appeal){  ?>
                            <li>
                                <p><strong>№ <?=$appeal->target->reestr_number?>-<?=$appeal->number_internal?> от <?=date("d.m.Y", $appeal->created_at)?></strong><br>
                                    <em><?=$appeal->target->name?></em><br>
                                    Структурное подразделение: Администрация города Красноярска<br>
                                    Статус: <?=$appeal->statusName?><p>


                            </li>
                        <?php }?>
                    </ul>

                    -->
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