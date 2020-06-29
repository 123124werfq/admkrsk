<?php
	$row['link'] = '/collection?id='.$id_record.'&id_page='.$id_page;

	$loader = new \Twig\Loader\ArrayLoader([
	    'index' => $template,
	]);

	$twig = new \Twig\Environment($loader);

    $filter = new \Twig\TwigFilter('render', function ($string) {
    	return \frontend\widgets\SubcollectionWidget::widget(['data'=>$string]);
    },['is_safe' => ['html']]);
    $twig->addFilter($filter);
?>
<div class="collection-element">
	<?=$twig->render('index', $row);?>
</div>