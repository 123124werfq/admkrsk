<?php
    use \common\models\CollectionColumn;
    use \common\models\CollectionRecord;

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
                foreach ($recordData[$alias] as $id_subrecord => $subrecord)
                {
                    /*$replace .= frontend\widgets\CollectionRecordWidget::widget([
                        'collectionRecord'=>CollectionRecord::findOne($id_subrecord),
                        'renderTemplate'=>true,
                        'templateAsElement'=>true,
                    ]);*/

                    $templateValues[$alias] = $value;
                }
            }
            else
                $templateValues[$alias] = $columns[$alias]->getValueByType($value);
        }
        else
            $templateValues[$alias] = $value;

        if (isset($templateValues[$alias]) && is_array($templateValues[$alias]))
            if(is_string(reset($templateValues[$alias])))
                $templateValues[$alias] = implode('', $templateValues[$alias]);

        //$template = str_replace('{'.$alias.'}', $replace , $template);
    }

    $loader = new \Twig\Loader\ArrayLoader([
        'index' => $template,
    ]);

    $twig = new \Twig\Environment($loader);

    $filter = new \Twig\TwigFilter('render', function ($string) {
        return \frontend\widgets\SubcollectionWidget::widget(['data'=>$string]);
    },['is_safe' => ['html']]);
    $twig->addFilter($filter);

    echo $twig->render('index', $templateValues);
?>