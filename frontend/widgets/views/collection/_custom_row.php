<?php
	preg_match_all ("/{(.+?)}/is", $template, $matches);

	if (!empty($matches[1]))
	{
		foreach ($matches[1] as $key => $alias)
		{
			if ($alias=='link')
				continue;

			if (isset($row[$alias]))
				$replate = $row[$alias];
			else
				$replate = '';

			$template = str_replace('{'.$alias.'}', $replate, $template);
		}

		$template = str_replace('{link}', '/collection?id='.$id_record.'&id_page='.$id_page,$template);
	}
?>
<div class="collection-element">
	<?=$template?>
</div>