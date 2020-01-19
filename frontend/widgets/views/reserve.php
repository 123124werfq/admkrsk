<?php

?>
<div id="hr_vote_view">
    <!--
    <table class="ms-formtoolbar search" cellpadding="2" cellspacing="0" border="0" width="100%">
        <tbody>
        <tr valign="top">
            <td>
                Категория должностей<br>
                <select name="" style="width:200px;" filterfield="PostCategory">
                    <option selected="selected" value=""></option>
                    <option value="1">Высшая</option>
                    <option value="2">Главная</option>
                    <option value="3">Ведущая</option>
                    <option value="4">Руководитель муниципального учреждения или предприятия</option>
                </select>
            </td>
            <td>
                Поиск по фамилии<br>
                <input type="text" style="width:220px;" value=""></td>
            <td>
                <br><button type="button" class="button2" style="width:100px;">Поиск</button>
            </td>
        </tr>
        </tbody>
    </table>
    -->
    <div style="padding:2px;">Количество резервистов: <?=count($reservers)?></div>
    <table width="100%" class="ms-listviewtable border" border="0" cellspacing="0" cellpadding="2">
        <tbody><tr class="hr_header" valign="top">
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


        </tbody></table>
</div>
