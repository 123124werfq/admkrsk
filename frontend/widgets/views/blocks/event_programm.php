<?php 
    use common\components\helper\Helper;

    $content = (!empty($blockVars['collection']))?$blockVars['collection']->value:0;

    echo Helper::runContentWidget($content,$page,[],['template_view'=>'program/program']);
?>