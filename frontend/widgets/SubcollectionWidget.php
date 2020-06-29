<?php
namespace frontend\widgets;

use Yii;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
use yii\data\Pagination;

class SubcollectionWidget extends \yii\base\Widget
{
    public $attributes = [];

    public $id_collection;

    public $column_alias; // колонки для отображения
    public $template = 'table'; // шаблон отображения

    public $data = [];

    public $page;

    public function run()
    {
        $setting = '';

    	//$model = Collection::find()->where(['id_collection'=>$this->id_collection])->one();
        //$column = CollectionColumn::find()->where(['id_collection'=>$model->id_collection,'alias'=>$this->column_alias])->one();

        $records = CollectionRecord::find()->where(['id_record' => array_keys($this->data)])->indexBy('id_record')->all();

        if (empty($records))
            return '';

        $collection = current($records);
        $template = $collection->collection->template_element;

        if (empty($template))
            return '';

        $output = '';

        foreach ($this->data as $id_record => $label)
        {
            if (isset($records[$id_record]))
            {
                $data = $records[$id_record]->getDataAsString(true,true);
                $output .= CollectionColumn::renderCustomValue($template,$data);
            }
        }

        return $output;
    }
}