<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>

<body>
<table class="table table-striped vote" border="1">
    <thead>
    <tr>
        <td>
            №
        </td>
        <td>
            ФИО кандидата
        </td>
        <td>
            Группы должностей
        </td>
        <?php foreach($data->experts as $expert){?>
            <td>
                <?=$expert->name?>
            </td>
        <?php } ?>
        <td>
            Итого
        </td>
    </tr>
    </thead>
    <?php
    $count = 1;
    $positionTotal = [];
    foreach ($data->profiles as $profile)
    {
        $positionTotal[$profile->id_profile] = [];
        ?>
        <tr>
            <th scope="row">
                <?=$count++?>
            </th>
            <td valign="top">
                <?=$profile->name?>
            </td>
            <td valign="top">
                <?php
                foreach ($profile->positions as $position)
                    echo $position->positionName . "<br>";
                ?>
            </td>
            <?php

            foreach ($data->experts as $expert)
            {
                echo "<td valign=\"top\">";

                foreach ($profile->positions as $position)
                {
                    if(!isset($positionTotal[$profile->id_profile][$position->id_profile_position]))
                        $positionTotal[$profile->id_profile][$position->id_profile_position] = 0;

                    $result = false;
                    $comment = '';
                    foreach ($votes as $vote)
                        if ($vote->id_expert == $expert->id_expert && $vote->id_profile == $profile->id_profile && $vote->id_record == $position->id_profile_position) {
                            $result = $vote->value;
                            $rr = $vote->id_record;
                            $positionTotal[$profile->id_profile][$position->id_profile_position] += $vote->value;

                            $comment = $vote->comment;
                        }

                    switch ($result) {
                        case 0:
                            echo '<span class="badge secondary">нет оценки</span>';
                            break;
                        case -1:
                            echo '<span class="badge badge-danger">отказать</span>';
                            break;
                        case 1:
                            echo '<span class="badge badge-success">включить</span>';
                            break;
                    }

                    echo "<br>";
                }
                echo "</td>";
            }

            ?>
            <td valign="top">
                <?php foreach ($positionTotal[$profile->id_profile] as $posid => $result){
                    if($result<0)
                        $final = 'отказать<br>';
                    else if($result>0)
                        $final = 'включить<br>';
                    else
                        $final = 'спорная<br>';


                    // если голосование уже завершено, то будут реальные результаты. подгрузим их напрямую
                    $rp = \common\models\HrProfilePositions::findOne($posid);
                    $fixedResult = \common\models\HrResult::find()->where(['id_contest' =>  $data->id_contest,'id_profile' => $profile->id_profile, 'id_record' => $rp->id_record_position])->one();

                    $ref = $fixedResult?$fixedResult->result:0;

                    if($ref==-1) echo 'отказать<br>';
                    if($ref==1) echo 'включить<br>';
                    if($ref==0) echo '<br>';

                    ?>

                <?php }?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
</body>
</html>