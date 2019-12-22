<?php
    preg_match_all ("/{(.+?)}/is", $template, $matches);

    if (!empty($matches[1]))
    {
        foreach ($matches[1] as $key => $alias)
        {
            if (isset($Record[$alias]))
            {
                if (isset($columns[$alias]))
                    $replace = $columns[$alias]->getValueByType($Record[$alias]);
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