<?php

namespace backend\models\forms;

use yii\base\Model;

class CollectionConvertForm extends Model
{
    public $id_collection;
    public $type;

    public function rules()
    {
        return [
            [['type'], 'required'],
            [['id_collection'], 'integer'],
            [['type'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_collection' => 'Список',
            'type'=>'Тип',
        ];
    }
}
