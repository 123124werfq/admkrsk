<div class="row">
    <div class="col-2-third">
        <p class="text-help">
            <?=Yii::t('site', 'Дата публикации (изменения)')?>: <span class="publish-date"><?=date('d.m.Y',$data->created_at)?></span> (<span class="update-date"><?=date('d.m.Y',$data->updated_at)?></span>)<br>
            <!--<?=Yii::t('site', 'Просмотров за год (всего)')?>: <?=$data->viewsYear?> (<?=$data->views?>)-->
        </p>
        <div class="subscribe">
            <div class="subscribe_left">
                <?=Yii::t('site', 'Поделиться')?>:
                <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
            </div>
            <div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> <?=Yii::t('site', 'Распечатать')?></a></div>
        </div>
    </div>
</div>