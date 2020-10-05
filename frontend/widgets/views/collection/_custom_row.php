<div class="collection-element">
	<?php
	foreach ($row as $alias => $value)
    {
        if (isset($columns[$alias]) && $columns[$alias]->isRelation())
        	$row[$alias] = $columns[$alias]->relatedData($value);
    }

    ?>
	<?=\common\components\helper\Helper::renderTwig($template,$row);?>
</div>