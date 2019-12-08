<div id="element<?=$element->id_element?>" class="col">
<?php foreach ($element->subForm->rows as $key => $row)
{
	echo '<div class="row">';
	foreach ($row->elements as $ekey => $element)
	{
		if (!empty($element->id_input))
			echo $this->render('_input',['input'=>$element->input,'element'=>$element,'model'=>$model,'form'=>$activeForm]);
		if (!empty($element->subForm))
			echo $this->render('_rows',['rows'=>$element->subForm->rows,'model'=>$model,'activeForm'=>$activeForm]);
		elseif (!empty($element->content))
		{
			$styles = $element->getStyles();
			echo '<div class="text-row" '.((!empty($styles))?'style="'.implode(';',$styles).'"':'').'>'.$element->content.'</div>';
		}
	}
	echo '</div>';
}
?>
</div>