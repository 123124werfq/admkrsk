<?php foreach ($rows as $key => $row)
{
	echo '<div class="row">';
	foreach ($row->elements as $ekey => $element)
	{
		if (!empty($element->input))
			echo $this->render('_input',['input'=>$element->input,'element'=>$element,'model'=>$model,'form'=>$activeForm,'arrayGroup'=>$arrayGroup]);
		if (!empty($element->subForm))
			echo $this->render('_subform',['element'=>$element,'model'=>$model,'activeForm'=>$activeForm,'arrayGroup'=>$arrayGroup]);
		elseif (!empty($element->content))
		{
			$styles = $element->getStyles();
			echo '<div id="element'.$element->id_element.'" class="text-row" '.((!empty($styles))?'style="'.implode(';',$styles).'"':'').'>'.$element->content.'</div>';
		}
	}
	echo '</div>';
}
?>