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
                    <?php if (count($service->activeTargets)==1 && !empty($service->activeTargets[0]->id_form))
                        echo '<a href="create?id_service='.$service->id_service.'&id_target='.$service->targets[0]->id_target.'" class="btn-group_item btn btn__secondary">Направить заявление</a>';
                    ?>
                    <a href="#" class="btn-group_item btn btn__gray">Подать жалобу</a>
                </div>

                <?php if (count($service->activeTargets)>1){?>
                <div class="file-list">
                    <?php foreach ($service->activeTargets as $key => $target) {?>
                        <div class="file-item">
                            <div class="file-td file-td__date"><?=$target->reestr_number?></div>
                            <div class="file-td file-td__name"><?=$target->name.' '.$target->place?></div>
                            <div class="file-td file-td__control">
                                <a href="create?id_service=<?=$service->id_service?>&id_target<?=$target->id_target?>" class="btn btn__secondary btn__block-sm">Направить заявление</a>
                            </div>
                        </div>
                    <?php }?>
                </div>
                <?php }?>

                <?php if (!empty($service->firms)){?>
                <h2>Органы, оказывающие услугу:</h2>
                <?php foreach ($service->firms as $key => $record){
                        $data = $record->getData(true);
                ?>
                    <p><?=$data['department']??''?></p>
                    <div class="content">
                        <div class="table-reponsive">
                            <table class="label-table">
                                <tr>
                                    <td>Адрес:</td>
                                    <td><?=nl2br($data['office']??'')?></td>
                                </tr>
                                <tr>
                                    <td>Телефон:</td>
                                    <td><?=nl2br($data['phones']??'')?></td>
                                </tr>
                                <tr>
                                    <td>Режим работы:</td>
                                    <td><?=$data['worktime']??''?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php
                    }}
                ?>

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