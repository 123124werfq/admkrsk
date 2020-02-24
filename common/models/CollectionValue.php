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

    public function getValue()
    {
        if (empty($value))
            return $value;

        $value = $this->value;

        switch ($this->column->type)
        {
            case self::TYPE_DISTRICT:
                return District::findOne((int)$value);
                break;
            case self::TYPE_REGION:
                return Region::findOne((int)$value)
                break;
            case self::TYPE_SUBREGION:
                return Subregion::findOne((int)$value);
                break;
            case self::TYPE_CITY:
                return City::findOne((int)$value);
                break;
            case self::TYPE_COLLECTION:
                return CollectionRecord::find(['where'=>$value])->one();;
                break;
            case self::TYPE_COLLECTIONS:
                return CollectionRecord::find(['where'=>$value])->all();;
                break;
            case self::TYPE_STREET:
                return Street::findOne((int)$value);
                break;
            case self::TYPE_FILE:
                if (is_array($value))
                {
                    $ids = [];
                    foreach ($value as $key => $data)
                    {
                        if (is_numeric($data))
                            $ids[] = $data;
                        else if (!empty($data['id']))
                            $ids[] = $data['id'];
                    }

                    if (empty($ids))
                        return false;

                    return Media::find()->where(['id_media'=>$ids])->all();
                }
                else
                    return false;

                break;
            case self::TYPE_FILE_OLD:
                $slugs = explode('/', $value);
                return $slugs;
                break;
            case self::TYPE_IMAGE:

                return Media::find()->where(['id_media'=>$value])->one();
                break;
            default:
                return $value;
                break;
        }

        return '';
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
        $value = $this->getValue();

        if (empty($value))
            return $value;

        switch ($this->column->type)
        {
            case self::TYPE_DATE:
                return date('d.m.Y',$value);
                break;
            case self::TYPE_DATETIME:
                return date('d.m.Y H:i',$value);
                break;
            case self::TYPE_DISTRICT:
                return $model->name;
                break;
            case self::TYPE_REGION:
                return $model->name;
                break;
            case self::TYPE_SUBREGION:
                return $model->name;
                break;
            case self::TYPE_CITY:
                return $city->name;
                break;
            case self::TYPE_STREET:
                return $city->name;
                break;
            case self::TYPE_FILE:
                $output = [];

                foreach ($value as $key => $media)
                    $output[] = '<a href="'.$media->getUrl().'" download><nobr>'.$media->name.'</nobr><a>';

                return implode('<br>', $output);
                break;
            case self::TYPE_FILE_OLD:
                $slugs = explode('/', $value);
                return '<a href="'.$value.'">'.array_pop($slugs).'<a>';
                break;
            case self::TYPE_IMAGE:
                $file_uploaded = $media->showThumb(['w'=>200,'h'=>200]);
                return '<img src="'.$file_uploaded.'" />';

                break;
            default:
                if (is_array($value))
                    return implode('<br>', $value);

                return $value;
                break;
        }

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
