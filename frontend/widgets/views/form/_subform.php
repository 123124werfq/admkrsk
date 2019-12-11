<div id="element<?=$element->id_element?>" class="col">
<?php foreach ($element->subForm->rows as $key => $row)
{
	echo '<div class="row">';
	foreach ($row->elements as $ekey => $rowelement)
	{
		if (!empty($rowelement->id_input))
		{
			echo $this->render('_input',['input'=>$rowelement->input,'element'=>$rowelement,'subform'=>$element->subForm,'model'=>$model,'form'=>$activeForm]);
		}
		/*if (!empty($element->subForm))
			echo $this->render('_rows',['rows'=>$element->subForm->rows,'model'=>$model,,'subform'=>$element->subForm,'activeForm'=>$activeForm]);*/
		elseif (!empty($rowelement->content))
		{
			$styles = $rowelement->getStyles();
			echo '<div class="text-row" '.((!empty($styles))?'style="'.implode(';',$styles).'"':'').'>'.$rowelement->content.'</div>';
		}
	}
	echo '</div>';
}
?>
</div>