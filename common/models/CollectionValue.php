<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_collection_value".
 *
 * @property int $id_column
 * @property int $id_record
 * @property string|null $value
 *
 * @property DbCollectionColumn $column
 * @property DbCollectionRecord $record
 */
class CollectionValue extends \yii\db\ActiveRecord
{
    public $mongoValue;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_collection_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_column', 'id_record'], 'required'],
            [['id_column', 'id_record'], 'integer'],
            [['value'], 'safe'],
            /*[['id_column', 'id_record'], 'unique', 'targetAttribute' => ['id_column', 'id_record']],
            [['id_column'], 'exist', 'skipOnError' => true, 'targetClass' => DbCollectionColumn::className(), 'targetAttribute' => ['id_column' => 'id_column']],
            [['id_record'], 'exist', 'skipOnError' => true, 'targetClass' => DbCollectionRecord::className(), 'targetAttribute' => ['id_record' => 'id_record']],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_column' => 'Колонка',
            'id_record' => 'Запись',
            'value' => 'Значение',
        ];
    }

    public function save()
    {

    }

    protected function prepareMongoDate($value, $column)
    {
        $output = [];

        $value_index = 'col'.$column->id_column;
        $search_index = 'col'.$column->id_column.'_search';

        $output[$value_index] = $value;

        switch ($column->type)
        {
            case CollectionColumn::TYPE_INTEGER:
                $output[$value_index] = (float)$value;
                break;
            case CollectionColumn::TYPE_CHECKBOXLIST:
                if (is_array($value))
                    $output[$search_index] = implode("\r\n", $value);
                else
                    $output[$search_index] = '';
                break;
            case CollectionColumn::TYPE_MAP:
                $output[$search_index] = implode(' ', $value);
                break;
            case CollectionColumn::TYPE_COLLECTION:
                $label = $this->getLabelsByID($value,$column);
                if (count($label)>0)
                    $output[$search_index] = array_shift($label);
                break;
            case CollectionColumn::TYPE_COLLECTIONS:
                $output[$search_index] = json_encode($this->getLabelsByID($value,$column),JSON_UNESCAPED_UNICODE);
                break;
            //case CollectionColumn::TYPE_COUNTRY:
            case CollectionColumn::TYPE_DISTRICT:
            case CollectionColumn::TYPE_STREET:
            case CollectionColumn::TYPE_CITY:
            case CollectionColumn::TYPE_REGION:
            case CollectionColumn::TYPE_SUBREGION:
            case CollectionColumn::TYPE_DATE:
            case CollectionColumn::TYPE_DATETIME:
            case CollectionColumn::TYPE_SERVICETARGET:
            case CollectionColumn::TYPE_SERVICE:
                $output[$search_index] = $column->getValueByType($value);
                break;
            case CollectionColumn::TYPE_FILE:
            case CollectionColumn::TYPE_IMAGE:
                $output[$search_index] = $value['name']??'';
                break;
                break;
            default:
                $output[$value_index] = $value;
                if (is_array($value))
                    $output[$search_index] = is_array($value)?json_encode($value,JSON_UNESCAPED_UNICODE):$value;

                break;
        }

        return $output;
    }

    public function __toString()
    {
        return $this->value;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColumn()
    {
        return $this->hasOne(CollectionColumn::className(), ['id_column' => 'id_column']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(CollectionRecord::className(), ['id_record' => 'id_record']);
    }
}
