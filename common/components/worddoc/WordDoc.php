<?php
namespace common\components\worddoc;

use Yii;
use PhpOffice\PhpWord\TemplateProcessor;
use common\models\CollectionColumn;
use common\models\CollectionRecord;


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

    public static function makeDocByForm($form, $data, $templatePath, $addData=[])
    {
        $root = Yii::getAlias('@app');

        $template = new TemplateProcessor($templatePath);

        $columns = $form->collection->getColumns()->indexBy('alias')->all();

        $stringData = WordDoc::convertDataToString($data,$columns);

        foreach ($addData as $key => $value)
            $stringData[$key] = $value;

        foreach ($stringData as $alias => $value)
        {
            if (isset($columns[$alias]) && $columns[$alias]->type==CollectionColumn::TYPE_JSON)
            {
                $value = json_decode($value,true);

                if (is_string($value))
                    $value = json_decode($value,true);

                if (!empty($value))
                {
                    $template->cloneRow($alias.'.'.key($value[0]), count($value));

                    $i = 1;
                    foreach ($value as $rkey => $row)
                    {
                        foreach ($row as $tkey => $td)
                            $template->setValue($alias.".".$tkey."#$i", $td);

                        $i++;
                    }
                }
            }
            else if (isset($stringData[$alias.'_file']) && $columns[$alias]->type==CollectionColumn::TYPE_IMAGE)
            {
                $template->setImageValue($alias.'_image', $stringData[$alias.'_file']);
                $template->setValue($alias, $value);
            }
            elseif (isset($columns[$alias]) && $columns[$alias]->type==CollectionColumn::TYPE_COLLECTIONS)
            {
                if (empty($value))
                    $template->deleteBlock($alias);
                else
                {
                    $records = CollectionRecord::find()->where(['id_record'=>array_keys($value)])->all();

                    if (empty($records))
                        $template->deleteBlock($alias);
                    else
                    {
                        $rcolumns = $records[0]->collection->columns;

                        $records_string = [];
                        foreach ($records as $rkey => $record)
                        {
                            $records_string[] = WordDoc::convertDataToString($record->getData(true),$rcolumns);
                        }

                        $template->cloneBlock($alias, 0, true, false, $records_string);
                    }
                }
            }
            else
                $template->setValue($alias, $value);
        }

        $export_path = $root."/runtime/templates/".time().md5(serialize($data)).'_out.docx';
        $template->saveAs($export_path);

        return $export_path;
    }

    public static function convertDataToString($data,$columns)
    {
        $string_output = [];

        //print_r($columns);

        foreach ($columns as $key => $col)
        {
            $col_alias = $col->alias;

            if ($col->type==CollectionColumn::TYPE_CHECKBOXLIST)
            {
                $values = $col->input->getArrayValues();

                $output = [];

                foreach ($values as $key => $value)
                {
                    $output[] = $value.(!empty($data[$col_alias]) && in_array($value, $data[$col_alias])?' - да':'- нет');
                }

                $string_output[$col->alias] = implode('<w:br/>', $output);
            }
            elseif (empty($data[$col_alias]))
                $string_output[$col_alias] = '';
            else if ($col->type==CollectionColumn::TYPE_DATE)
                $string_output[$col_alias] = date('d.m.Y',$data[$col_alias]);
            else if ($col->type==CollectionColumn::TYPE_DATETIME)
                $string_output[$col_alias] = date('d.m.Y H:i',$data[$col_alias]);
            else if ($col->type==CollectionColumn::TYPE_DISTRICT)
            {
                $model = \common\models\District::findOne($data[$col_alias]);

                if (!empty($model))
                    $string_output[$col_alias] = $model->name;
            }
            else if ($col->type==CollectionColumn::TYPE_COLLECTIONS)
                $string_output[$col->alias] = $data[$col_alias];
            else if ($col->type==CollectionColumn::TYPE_SERVICETARGET)
            {
                $model = \common\models\ServiceTarget::findOne($data[$col_alias]);

                if (!empty($model))
                    $string_output[$col_alias] = $model->name;

                $string_output[$col->alias] = $data[$col_alias];
            }
            else if ($col->type==CollectionColumn::TYPE_JSON)
            {
                $string_output[$col->alias] = $data[$col_alias];
            }
            else if ($col->type==CollectionColumn::TYPE_ADDRESS)
            {
                $string_output[$col->alias.'.country'] = $data[$col_alias]['country']??'';
                $string_output[$col->alias.'.region'] = $data[$col_alias]['region']??'';
                $string_output[$col->alias.'.subregion'] = $data[$col_alias]['subregion']??'';
                $string_output[$col->alias.'.city'] = $data[$col_alias]['city']??'';
                $string_output[$col->alias.'.district'] = $data[$col_alias]['district']??'';
                $string_output[$col->alias.'.street'] = $data[$col_alias]['street']??'';
                $string_output[$col->alias.'.house'] = $data[$col_alias]['house']??'';
                $string_output[$col->alias.'.room'] = $data[$col_alias]['room']??'';
                $string_output[$col->alias.'.postсode'] = $string_output[$col->alias.'.postalcode'] = $data[$col_alias]['postalcode']??'';
                $string_output[$col->alias.'.fullname'] = $string_output[$col->alias.'.fulladdress'] = $data[$col_alias]['fullname']??'';
                $string_output[$col->alias.'.lat'] = $data[$col_alias]['lat']??'';
                $string_output[$col->alias.'.lon'] = $data[$col_alias]['lon']??'';
            }
            else if ($col->type==CollectionColumn::TYPE_CITY)
            {
                $model = \common\models\City::findOne($data[$col_alias]);

                if (!empty($model))
                    $string_output[$col_alias] = $model->name;
            }
            else if ($col->type==CollectionColumn::TYPE_STREET)
            {
                $model = \common\models\Street::findOne($data[$col_alias]);

                if (!empty($model))
                    $string_output[$col_alias] = $model->name;
            }
            else if ($col->type==CollectionColumn::TYPE_REGION)
            {
                $model = \common\models\Region::findOne($data[$col_alias]);

                if (!empty($model))
                    $string_output[$col_alias] = $model->name;
            }
            else if ($col->type==CollectionColumn::TYPE_HOUSE)
            {
                $model = \common\models\House::findOne($data[$col_alias]);

                if (!empty($model))
                    $string_output[$col_alias] = $model->name;
            }
            else if ($col->type==CollectionColumn::TYPE_FILE || $col->type==CollectionColumn::TYPE_IMAGE)
            {
                if (is_array($data[$col_alias]))
                {
                    $ids = [];
                    foreach ($data[$col_alias] as $key => $data)
                    {
                        $ids[] = $data['id'];
                    }
                }
                else
                    $ids = $data[$col_alias];

                $medias = \common\models\Media::find()->where(['id_media'=>$ids])->all();

                $output = [];
                foreach ($medias as $key => $media)
                    $output[] = $media->name;

                if (count($output)>1)
                    $string_output[$col->alias] = implode('<w:br/>', $output);
                else if(count($output) == 1)
                {
                    $string_output[$col->alias] = $output[0];
                    $string_output[$col->alias.'_file'] = $media->getUrl();
                }
            }
            else if (!empty($col->input->id_collection))
            {
                $string_output[$col->alias] = implode('<w:br/>', $data[$col_alias]);
            }
            else
            {
                if (is_array($data[$col_alias]))
                    $string_output[$col->alias] = implode('<w:br/>', $data[$col_alias]);
                else
                    $string_output[$col->alias] = (string)$data[$col_alias];
            }
        }

        return $string_output;
    }
}
?>