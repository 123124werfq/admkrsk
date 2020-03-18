<?php

namespace backend\models\forms;

use common\models\Collection;
use Yii;
use yii\base\Model;

class CollectionRecordSearchForm extends Model
{
    public $id_collection;

    public $filters;
    public $columns;
    public $searchColumns;
    public $options;

    public function rules()
    {
        return [
            [['id_collection'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    public function getViewColumnsOrFirstColumn()
    {
        $options = json_decode($this->options, true);

        if (isset($options['columns']))
            return $options['columns'];
        return [
            [
                'id_column' => $this->collection->columns[0]->id_column,
                'group' => '',
                'show_for_searchcolumn' => '',
                'filelink' => '',
            ]
        ];
    }

    public function getSearchColumns()
    {
        $options = json_decode($this->options, true);

        if (isset($options['search'])) {
            return $options['search'];
        }

        return [
            [
                'id_column' => '',
                'type' => 0,
            ]
        ];
    }

    public function getViewFilters()
    {
        $options = json_decode($this->options, true);

        if (isset($options['filters']) && !is_array($options['filters']))
            return json_decode($options['filters']);

        return [];
    }

    public function getCollection()
    {

        return Collection::findOne($this->id_collection);
    }
}
