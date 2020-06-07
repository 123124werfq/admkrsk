<?php
    use \common\models\CollectionColumn;
    use \common\models\CollectionRecord;

    preg_match_all ("/{{(.+?)}/is", $template, $matches);

    $templateValues = [];

    if (!empty($matches[1]))
    {
        foreach ($matches[1] as $key => $alias)
        {
            if (isset($recordData[$alias]))
            {
                if (isset($columns[$alias]))
                {
                    if ($columns[$alias]->isRelation())
                    {
                        $replace = '';
                        foreach ($recordData[$alias] as $id_subrecord => $subrecord)
                        {
                            $replace .= frontend\widgets\CollectionRecordWidget::widget([
                                'collectionRecord'=>CollectionRecord::findOne($id_subrecord),
                                'renderTemplate'=>true,
                                'templateAsElement'=>true,
                            ]);
                        }
                    }
                    else
                        $templateValues[$alias] = $columns[$alias]->getValueByType($recordData[$alias]);
                }
                else
                    $templateValues[$alias] = $recordData[$alias];
            }
            else
                $templateValues[$alias] = '';

            if (is_array($templateValues[$alias]))
                $templateValues[$alias] = implode('', $templateValues[$alias]);

            //$template = str_replace('{'.$alias.'}', $replace , $template);
        }

        $loader = new \Twig\Loader\ArrayLoader([
            'index' => $template,
        ]);
        $twig = new \Twig\Environment($loader);

        echo $twig->render('index', $templateValues);
    }
?>