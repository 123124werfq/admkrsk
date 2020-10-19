<?php
    //preg_match_all ("/{{(.+?)}/is", $template, $matches);

    $templateValues = [];

    /*if (!empty($matches[1]))
    {*/
    foreach ($recordData as $alias => $value)
    {
        /*if (strpos($alias, '|'))
            $alias = substr($alias, 0, $alias, '|'));*/

        if (isset($columns[$alias]))
        {
            if ($columns[$alias]->isRelation())
            {
                $templateValues[$alias] = $value;
            }
            else
                $templateValues[$alias] = $value; //$columns[$alias]->getValueByType($value);
        }
        else
            $templateValues[$alias] = $value;

        /*if (isset($templateValues[$alias]) && is_array($templateValues[$alias]) && !$columns[$alias]->isRelation())
            if (is_string(reset($templateValues[$alias])))
                $templateValues[$alias] = implode('', $templateValues[$alias]);*/
    }

    echo \common\components\helper\Helper::renderTwig($template,$templateValues);
?>