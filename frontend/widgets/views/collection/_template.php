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
                //$replace = '';
                //foreach ($recordData[$alias] as $id_subrecord => $subrecord)
                //{
                    /*$replace .= frontend\widgets\CollectionRecordWidget::widget([
                        'collectionRecord'=>CollectionRecord::findOne($id_subrecord),
                        'renderTemplate'=>true,
                        'templateAsElement'=>true,
                    ]);*/

                    $templateValues[$alias] = $value;
                //}
            }
            else
                $templateValues[$alias] = $columns[$alias]->getValueByType($value);
        }
        else
            $templateValues[$alias] = $value;

        if (isset($templateValues[$alias]) && is_array($templateValues[$alias]) && !$columns[$alias]->isRelation())
            if (is_string(reset($templateValues[$alias])))
                $templateValues[$alias] = implode('', $templateValues[$alias]);

        //$template = str_replace('{'.$alias.'}', $replace , $template);
    }

    return \common\components\helper\Helper::renderTwig($template,$templateValues);
?>