<table class="table" width="400">
<?php 
foreach ($columns as $key => $column)
{
    $props = $column->getTemplateProperties();

    if (!empty($props))
        foreach ($props as $alias => $prop )
            echo '<tr><th width="100">' . ($alias) . '</th><td>' . $column->name.'</td><td><small>'.$prop.'</small></td></tr>';
} 
?>
</div>
</table>