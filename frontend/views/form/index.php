<?php
/* @var common\models\Page $page */

/**
 * @param string $tag
 * @return array
 */
function parseAttributesFromTag($tag){
    $pattern = '/(\w+)=[\'"]([^\'"]*)/';

    preg_match_all($pattern,$tag,$matches,PREG_SET_ORDER);

    $result = [];
    foreach($matches as $match){
        $attrName = $match[1];
        $attrValue = is_numeric($match[2])? (int)$match[2]: trim($match[2]);
        $result[$attrName] = $attrValue;
    }

    return $result;
}
?>
<div class="main">
    <div class="container">
        <div class="row">

        </div>
        <div class="row">
            <div class="col-2-third order-xs-1">
            	<div class="content">
                    <h1>Предпросмотр формы</h1>
                    <?=frontend\widgets\FormsWidget::widget(['id_form'=>$model->id_form])?>
            	</div>
            </div>
            <div class="col-third order-xs-0">
            </div>
        </div>

        <hr class="hr hr__md"/>
    </div>
</div>
