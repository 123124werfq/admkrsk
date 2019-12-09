<?php
namespace common\components\worddoc;

use Yii;
use PhpOffice\PhpWord\TemplateProcessor;
use common\models\CollectionColumn;

class WordDoc
{
    protected static function ru_date($format, $date = false)
    {
        $months = explode("|", '|января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря');
        $format = str_replace("%B", $months[date('n', $date)],$format);

        return strftime($format, $date);
    }

	public static function makeDoc($data, $template)
    {
        $root = Yii::getAlias('@app');

        $template = new TemplateProcessor($root.'/template/'.$template);

        $name = time();

        $template->cloneRow('name', count($data));

        foreach ($data as $key => $value)
        {
            $i = $key+1;

            $template->setValue("name#$i", $key);
            $template->setValue("value#$i", $value);
        }

        $template->saveAs($root."/runtime/$name.docx");

        return true;
    }

    public static function makeDocByForm($form, $data, $templatePath)
    {
        $root = Yii::getAlias('@app');

        $template = new TemplateProcessor($templatePath);

        $columns = $form->collection->getColumns()->indexBy('alias')->all();

        foreach ($data as $alias => $value)
        {
            if (isset($columns[$alias]) && $columns[$alias]->type==CollectionColumn::TYPE_JSON)
            {
                $value = json_decode($value,true);

                if (is_string($value))
                    $value = json_decode($value,true);

                $template->cloneRow($alias.'_1', count($value));

                foreach ($value as $rkey => $row)
                {
                    $i = $rkey+1;

                    foreach ($row as $tkey => $td)
                        $template->setValue($alias."_".($tkey+1)."#$i", $td);
                }
            }
            else
                $template->setValue($alias, $value);
        }

        $export_path = $root."/runtime/templates/".time().md5(serialize($data)).'_out.docx';
        $template->saveAs($export_path);

        return $export_path;
    }
}
?>