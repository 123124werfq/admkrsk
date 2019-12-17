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

    $forms = $service->getForms()->all();
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
                    <?php if (count($forms)==1)
                        echo '<a href="create?&id_form='.$forms[0]->id_form.'" class="btn-group_item btn btn__secondary">Направить заявление</a>';
                    ?>
                    <?php if ($service->isAppealable()){?>
                        <a href="complaint" class="btn-group_item btn btn__gray">Подать жалобу</a>
                    <?php }?>
                </div>

                <?php if (count($forms)>1){?>
                <div class="file-list">
                    <?php foreach ($forms as $key => $form) {?>
                        <div class="file-item">
                            <div class="file-td file-td__date"><?=$form->name?></div>
                            <div class="file-td file-td__name"><?=$form->fullname?></div>
                            <div class="file-td file-td__control">
                                <a href="create?id_form=<?=$form->id_form?>" class="btn btn__secondary btn__block-sm">Направить заявление</a>
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