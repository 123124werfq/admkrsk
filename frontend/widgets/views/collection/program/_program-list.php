<div class="program">
<?php
    $i = 1;
    foreach ($groups as $group => $allrows)
    {
        if (!empty($group))
            echo '<h2 class="program_date">'.$group.'</h2>';

        foreach ($allrows as $key => $row){?>
        <div class="program-event">
            <div class="program_row">            
            <?php foreach ($columns as $ckey => $column) {?>
                <div class="program_col-main"><?php
                    if (isset($row[$column->alias]))
                    {
                        $value = $column->getValueByType($row[$column->alias]);

                        if ($column->is_link)
                            echo Html::a($value, ['/collection','id'=>$id_record,'id_page'=>$page->id_page,'id_collection'=>$id_collection]);
                        elseif (!empty($columnsOptions[$column->alias]['filelink']) && !empty($row[$columnsOptions[$column->alias]['filelink']]))
                            echo '<a href="'.$row[$columnsOptions[$column->alias]['filelink']].'" download>'.$value.'</a>';
                        else
                            echo $value;
                    }?>
                </div>
            <?php }?>
            </div>
        </div>
<?php   }
    }
?>
</div>