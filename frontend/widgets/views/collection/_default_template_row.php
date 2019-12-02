<?php
foreach ($columns as $key => $column)
{
	if (isset($row[$column->id_column]) && $row[$column->id_column]!=='')
		echo '<p><b>'.$column->name.':</b>'.$row[$column->id_column].'</p>';
}