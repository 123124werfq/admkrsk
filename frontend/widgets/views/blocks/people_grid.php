<div class="goslinks">
    <div class="container">
        <div class="goslinks-list">
<?php if (!empty($blockVars['peoples'])){
    $records = \common\models\CollectionRecord::find()->where(['id_record'=>json_decode($blockVars['peoples']->value,true)])->all();

    if (!empty($records))
    {
        foreach ($records as $key => $record)
        {
            $data = $record->getData(true);
            $photo = $record->getMedia('photo',true);
?>
            <div class="goslinks-col goslinks-col__half">
                <div class="person-card">
                    <?php if(!empty($photo)){?>
                        <img class="person-card_img" src="<?=$photo->showThumb(['w'=>160,'h'=>160])?>" alt=""/>
                    <?php }?>
                    <div class="person-card_content">
                        <h4 class="person-card_title"><?=$data['fio']??''?></h4>
                        <p class="person-card_subtitle">
                            <?=nl2br($data['subtitle']??'')?>
                        </p>
                        <p>
                            <?=nl2br($data['description']??'')?>
                        </p>
                        <div class="person-card_contact">
                            <a class="person-card_contact-item person-card_contact-item__phone" href="tel:+<?=preg_replace('/\D/', '', $data['phone']??'')?>"><?=$data['phone']??''?></a>
                            <a class="person-card_contact-item person-card_contact-item__email" href="mailto:<?=$data['email']??''?>"><?=$data['email']??''?></a>
                        </div>
                    </div>
                </div>
            </div>
<?php }}}?>
        </div>
    </div>
</div>