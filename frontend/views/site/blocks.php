<?php
foreach ($page->getBlocks()->where(['state'=>1])->all() as $key => $block)
{
	$widget = $block->getWidget();

	if (!empty($widget))
		echo $widget::widget(['page' => $page,'block'=>$block]);
	elseif (!empty($block->code))
		echo $block->code;
	else
		echo frontend\widgets\Block::widget(['page' => $page,'block'=>$block]);
}
?>