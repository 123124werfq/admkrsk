<?php

namespace backend\models\forms;

use yii\base\Model;
use common\models\Media;
use common\components\multifile\MultiUploadBehavior;

class CollectionRecordForm extends Model
{
    public $id_column_coords, $columns;

    public function rules()
    {
        return [
            [['id_column_coords'], 'integer'],
            [['columns'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_column_coords' => 'Колонка отображения координат',
            'columns'=>'Колонки отображения данных',
        ];
    }
}
