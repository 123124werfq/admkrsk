<?php 
    $attributes = [
        'reestr_number',
        'fullname',
        'name',
        'keywords',
        'addresses',
        'result',
        //'client_type',
        'client_category',
        'duration',
        'refuse',
        'documents',
        'price',
        'appeal',
        'legal_grounds',
        'regulations',
        'regulations_link',
        'duration_order',
        'availability',
        'procedure_information',
        'max_duration_queue'
        //'online'
    ];
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2"><?=$service->name?></h1>
                <div class="content">
                    <div class="table-responsive">
                        <table class="label-table">
                            <?php foreach ($attributes as $key => $attr) {
                                if (empty($service->$attr))
                                    continue;
                            ?>
                                <tr>
                                    <td><?=$service->attributeLabels()[$attr]?></td>
                                    <td>
                                        <?=$service->$attr?>
                                    </td>
                                </tr>
                            <?php }?>
                        </table>
                    </div>
                </div>
                <div class="btn-group">
                    <?php if (!empty($service->id_form && empty($service->targets)))
                        echo '<a href="create?id='.$service->id_service.'" class="btn-group_item btn btn__secondary">Направить заявление</a>';
                    else if (count($service->targets)==1 && !empty($service->targets[0]->id_form))
                        echo '<a href="create?id_target='.$service->targets[0]->id_target.'" class="btn-group_item btn btn__secondary">Направить заявление</a>';
                    ?>
                    <a href="#" class="btn-group_item btn btn__gray">Подать жалобу</a>
                </div>

                <?php if (count($service->targets)>1){?>
                <div class="file-list">
                    <?php foreach ($service->targets as $key => $target) {?>
                        <div class="file-item">
                            <div class="file-td file-td__date"><?=$target->reestr_number?></div>
                            <div class="file-td file-td__name"><?=$target->name?></div>
                            <div class="file-td file-td__control">
                                <a href="#" class="btn btn__secondary btn__block-sm">Направить заявление</a>
                            </div>
                        </div>
                    <?php }?>
                </div>
                <?php }?>

                <!--h2>Органы, оказывающие услугу:</h2>
                <p>Управление учета и реализации жилищной политики администрации города</p>

                <div class="content">
                    <div class="table-reponsive">
                        <table class="label-table">
                            <tr>
                                <td>Адрес:</td>
                                <td>г. Красноярск, ул. К. Маркса, 93 , Каб. 615</td>
                            </tr>
                            <tr>
                                <td>Телефон:</td>
                                <td>(391)226-15-68, (391)226-13-24</td>
                            </tr>
                            <tr>
                                <td>Режим работы:</td>
                                <td>Прием заявлений, выдача результата: понедельник - четверг с 9.00 до 13.00</td>
                            </tr>
                        </table>
                    </div>
                </div-->

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