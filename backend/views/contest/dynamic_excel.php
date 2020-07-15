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

<?php 
    foreach($votelist as $cindex => $votes){
        if(!count($votes))
            continue;
?>

    <table class="table table-striped vote">
        <thead>
        <tr>
            <td>
                №
            </td>
            <td>
                Наименование проекта
            </td>
            <?php foreach($experts[$cindex] as $expertId => $expertName){?>
                <td style="min-width: 100px;">
                    <?=$expertName?>
                </td>
            <?php } ?>
            <td style="min-width: 200px;">
                Итого
            </td>
        </tr>
        </thead>
        <?php foreach($votes as $profileId => $vl){ 
            $result = 0;
        ?>
        <tr>
            <td><?=$profileId?></td>
            <td><?=$vl['name']?></td>
            <?php foreach($experts[$cindex] as $expertId => $expertName){
                $currentVal = $vl['votebyexpert'][$expertId]??0;
                $result += $currentVal;

                $voteName = 'не голосовал';

                if($vote_type == 'Баллы')
                {
                    $voteName = $currentVal;
                }
                else
                {
                    if($currentVal>0)
                        $voteName = '<span class="badge badge-success">ЗА</span>';
                    else if($currentVal<0)
                        $voteName = '<span class="badge badge-danger">ПРОТИВ</span>';
                }
                ?>
                <td style="min-width: 100px;">
                    <?=$voteName?>
                </td>
            <?php } ?>  
            <td>
                <?php 
                    if($vote_type == 'Баллы')
                    {
                        echo $result;
                    }
                    else
                    {
                        if($result<0)
                            $final = '<span class="badge badge-danger">ПРОТИВ</span>';
                        else if($result>0)
                            $final = '<span class="badge badge-success">ЗА</span>';
                        else
                            $final = 'спорная';
                        $ref = 0;
                    ?>
                    <!--select name="results">
                        <option value="0"></option>
                        <option value="-1" <?=($ref==-1)?'selected':''?>>ПРОТИВ</option>
                        <option value="1" <?=($ref==1)?'selected':''?>>ЗА</option>
                    </select-->&nbsp;<?=$final?><br>
                <?php
                    }
                ?>
                </td>         
        </tr>
        <?php } ?>
    </table>

<?php 
    }
?>

</body>
</html>