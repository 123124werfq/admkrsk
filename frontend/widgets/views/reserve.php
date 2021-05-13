<?php
    use yii\helpers\Html;
    use yii\widgets\Pjax;
?>
<div id="hr_vote_view">

    <form class="search-table" data-hash="hrreserve" action="">
        <?=Html::textInput('fio',Yii::$app->request->get('fio'),['class'=>'form-control','placeholder'=>'ФИО','max-lenght'=>255]);?>
        <?=Html::dropDownList('id_record_position',Yii::$app->request->get('id_record_position'),$groups,['class'=>'form-control','prompt'=>'Группа']);?>
    </form>

    <?php Pjax::begin([
        'id' => 'hrreserve',
        'timeout'=>5000
        //'enablePushState' => false,
    ]) ?>

    <div style="padding:0px 0px 10px; text-align: right;">Количество резервистов: <?=count($reservers)?></div>
    <table width="100%" class="ms-listviewtable border" border="0" cellspacing="0" cellpadding="2">
        <tbody>
            <tr class="hr_header" valign="top">
                <th>
                    №
                </th>
                <th>
                    ФИО резервиста
                </th>
                <th>
                    Группы должностей
                </th>
                <th>
                    Дата включения в резерв
                </th>
            </tr>
        <?php
            $count = 0;
            foreach($reservers as $rid => $reserver){
                $count++;
                $span = count($reserver);
                foreach ($reserver as $key => $position) {
                    ?>
                    <tr valign="top">
                        <?php if($key==0) { ?>
                        <td rowspan="<?=$span?>" align="center"><?=$count?>.</td>
                        <td rowspan="<?=$span?>"><a href="/clerk/anketa_view?id=<?= $rid ?>" class="hr_vote hr_tab" target="_blank"><?= $position['name'] ?></a></td>
                        <?php } ?>
                        <td title="<?= $position['position'] ?>"><?= $position['position'] ?></td>
                        <td align="center" style="text-align:center;"><?= date("d.m.Y", $position['date']) ?></td>
                    </tr>
                    <?php
                }
            }
        ?>
        </tbody>
    </table>
    <?php Pjax::end(); ?>
</div>
