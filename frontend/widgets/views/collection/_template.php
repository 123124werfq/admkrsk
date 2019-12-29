<?php
    use \common\models\CollectionColumn;
    use \common\models\CollectionRecord;

    preg_match_all ("/{(.+?)}/is", $template, $matches);

    if (!empty($matches[1]))
    {
        foreach ($matches[1] as $key => $alias)
        {
            if (isset($Record[$alias]))
            {
                if (isset($columns[$alias]))
                {
                    if ($columns[$alias]->isRelation())
                    {
                        $replace = '';
                        foreach ($Record[$alias] as $id_subrecord => $subrecord)
                        {
                            $replace .= frontend\widgets\CollectionRecordWidget::widget([
                                'collectionRecord'=>CollectionRecord::findOne($id_subrecord),
                                'renderTemplate'=>true,
                                'templateAsElement'=>true,
                            ]);
                        }
                    }
                    else
                        $replace = $columns[$alias]->getValueByType($Record[$alias]);
                }
                else
                    $replace = $Record[$alias];
            }
            else
                $replace = '';


            if (is_array($replace))
                $replace = implode('', $replace);

            $template = str_replace('{'.$alias.'}', $replace , $template);
        }
    }
?>

<?=str_replace('\n', '', $template)?>