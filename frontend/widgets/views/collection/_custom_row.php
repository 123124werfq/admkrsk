<?php
	preg_match_all ("/{(.+?)}/is", $template, $matches);

	if (!empty($matches[1]))
	{
		foreach ($matches[1] as $key => $alias)
		{
			if (isset($row[$alias]))
				$template = str_replace('{'.$alias.'}', $row[$alias] , $template);
		}

		$template = str_replace('{link}', '/collection?id='.$id_record,$template);
	}
?>
<div class="collection-element">
<?=$template?>
</div>