<?php
namespace common\components\worddoc;

use Yii;
use PhpOffice\PhpWord\TemplateProcessor;

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

        foreach ($data as $alias => $value)
        {
            $template->setValue($alias, $value);
        }

        /*$template->cloneRow('name', count($data));

        foreach ($data as $key => $value)
        {
            $i = $key+1;

            $template->setValue("name#$i", $key);
            $template->setValue("value#$i", $value);
        }*/


        $export_path = $root."/runtime/templates/".time().md5(serialize($data)).'_out.docx';
        $template->saveAs($export_path);

        return $export_path;
    }
}
?>