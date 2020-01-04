<?php
	//preg_match_all ("/{(.+?)}/is", $template, $matches);

	$row['link'] = '/collection?id='.$id_record.'&id_page='.$id_page;

	$loader = new \Twig\Loader\ArrayLoader([
	    'index' => $template,
	]);
	$twig = new \Twig\Environment($loader);

	/*if (!empty($matches[1]))
	{
		foreach ($matches[1] as $key => $alias)
		{
			if ($alias=='link')
				continue;

			if (isset($row[$alias]))
			{
				if (isset($columns[$alias]))
					$replace = $columns[$alias]->getValueByType($row[$alias]);
				else
					$replace = $row[$alias];
			}
			else
				$replace = '';

			$template = str_replace('{'.$alias.'}', $replace, $template);
		}

		$template = str_replace('{link}', '/collection?id='.$id_record.'&id_page='.$id_page,$template);
	}*/
?>
<div class="collection-element">
	<?=$twig->render('index', $row);?>
</div>