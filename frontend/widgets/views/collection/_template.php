<?php
    use \common\models\CollectionColumn;
    use \common\models\CollectionRecord;

    preg_match_all ("/{(.+?)}/is", $template, $matches);

    $templateValues = [];

    if (!empty($matches[1]))
    {
        foreach ($matches[1] as $key => $alias)
        {
            if (isset($recorData[$alias]))
            {
                if (isset($columns[$alias]))
                {
                    if ($columns[$alias]->isRelation())
                    {
                        $replace = '';
                        foreach ($recorData[$alias] as $id_subrecord => $subrecord)
                        {
                            $replace .= frontend\widgets\CollectionRecordWidget::widget([
                                'collectionRecord'=>CollectionRecord::findOne($id_subrecord),
                                'renderTemplate'=>true,
                                'templateAsElement'=>true,
                            ]);
                        }
                    }
                    else
                        $templateValues[$alias] = $columns[$alias]->getValueByType($recorData[$alias]);
                }
                else
                    $templateValues[$alias] = $recorData[$alias];
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

    //str_replace('\n', '', $template)
?>