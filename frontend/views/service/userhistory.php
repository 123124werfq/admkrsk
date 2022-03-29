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
                            <th width="40%">Услуга</th>
                            <th width="35%">Состояние заявки</th>
                            <!--th>Оценка рассмотрения заявки на предоставление услуги</th-->
                        </tr>  
                        </thead>           
                        <?php foreach ($appeals as $appeal){  ?>
                        <tr>
                            <td><?=$appeal->number_common?><br><?=date("d.m.Y", $appeal->created_at)?></td>
                            <td>
                                <?php if(is_object($appeal->target)) { ?>
                                <?=$appeal->target->reestr_number?><br>
                                <?=$appeal->target->name?><br><br>
                                <?php } ?>
                                Номер: <?=$appeal->number_system?><br>
                                Дата регистрации: <?=$appeal->statusDate?><br>
                            </td>
                            <td>
                                <!--<?=$appeal->statusName?>-->
                                <?php
                                    foreach ($appeal->states as $status) {
                                        echo "Дата события: " . date("d-m-Y", $status->date);
                                        echo "<br>";
                                        echo $status->statusName();
                                        echo "<br><br>";

                                        if($status->state == "3")
                                        {
                                            echo "<a href='/workflow/archive?s={$appeal->number_internal}'>Скачать архив</a>";
                                        }
                                    }
                                ?>
                            </td>
                            <!--td>
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
                            </td-->
                        </tr>
                        <?php }?>
                    </table>
                    </div>
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