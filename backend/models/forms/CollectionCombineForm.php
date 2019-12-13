<?php

namespace backend\models\forms;

use common\models\User;
use Yii;
use yii\base\Model;

class CollectionCombineForm extends Model
{
    public $id_collection;
    public $id_collection_column;

    public $id_collection_from;
    public $id_collection_from_column;

    public $alias,$column_name;

    public function rules()
    {
        return [
            [['id_collection','id_collection_column','id_collection_from','id_collection_from_column'], 'required'],
            [['id_collection','id_collection_column','id_collection_from','id_collection_from_column'], 'integer'],
            [['alias','column_name'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_collection' => 'Список',
        ];
    }

}
