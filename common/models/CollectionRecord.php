<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\modules\log\behaviors\LogBehavior;
use yii\mongodb\Query;

/**
 * This is the model class for table "db_collection_record".
 *
 * @property int $id_record
 * @property int $id_collection
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property Collection $collection
 */
class CollectionRecord extends \yii\db\ActiveRecord
{
    public $data = null;

    public $loadData = [];
    public $loadDataAlias = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_collection_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'ord'], 'default', 'value' => null],
            [['id_collection', 'ord'], 'integer'],
            [['data_hash'], 'string'],
            [['data'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_record' => 'Id Record',
            'id_collection' => 'Список',
            'ord' => 'Ord',
            'data_hash'=>'Хэш данных',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
        ];
    }

    public function __get($name)
    {
        if (isset($this->loadDataAlias[$name]))
          return $this->loadDataAlias[$name];

       return parent::__get($name);
    }

    public function getLineValue($id_column=null)
    {
        $data = $this->getData();

        if (empty($id_column))
        {
            return implode(' ', $data);
        }
        else
            return $data[$id_column]??'Колонка не найдена';
    }

    protected function getLabelsByID($ids,$column)
    {
        $mongoLabels = [];

        if (!empty($ids))
        {
            $labels = (new \yii\db\Query())
                ->select(['value', 'id_record'])
                ->from('db_collection_value')
                ->where([
                    'id_record' => $ids,
                    'id_column'=>$column->input->id_collection_column
                ])->all();

            $labelsByIndex = [];

            foreach ($labels as $lkey => $data)
                $labelsByIndex[$data['id_record']] = $data['value'];

            if (is_array($ids))
                foreach ($ids as $key => $id)
                    $mongoLabels[$id] = $labelsByIndex[$id]??'';
            else
                $mongoLabels[$ids] = $labelsByIndex[$ids]??'';
        }

        return $mongoLabels;
    }

    public function getLabel()
    {
        $label = (!empty($this->collection->label)) ? $this->collection->label : [];

        $data = $this->getData(false);

        $output = [];

        foreach ($label as $key => $id_column) {
            if (!empty($data[$id_column]))
                $output[] = $data[$id_column];
        }

        return implode(', ',$output);
    }

    protected function getMongoDate($value, $column)
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
            case CollectionColumn::TYPE_DISTRICT:
            case CollectionColumn::TYPE_STREET:
            //case CollectionColumn::TYPE_COUNTRY:
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert) && !empty($this->data))
            $this->data_hash = md5(json_encode($this->data));

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!empty($this->data)) //&& (empty($changedAttributes['data_hash']) || $insert)
        {
            $columns = $this->collection->getColumns()->with(['input'])->all();

            // коллекция монги
            $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);

            unset($this->data['id_record']);

            // запись в postgree
            $insertData = [];
            $dataMongo = [];

            foreach ($columns as $key => $column)
            {
                $value = '';

                if (isset($this->data[$column->alias]))
                    $value = $this->data[$column->alias];
                elseif (isset($this->data[$column->id_column]))
                    $value = $this->data[$column->id_column];
                else
                    continue;

                if (!$insert)
                {
                    $count = Yii::$app->db->createCommand("SELECT count(*) FROM db_collection_value WHERE id_record = $this->id_record AND
                        id_column = $column->id_column")->queryScalar();
                }

                if (!$insert && !empty($count))
                    Yii::$app->db->createCommand()->update('db_collection_value',
                        ['value'=>is_array($value)?json_encode($value):$value],[
                        'id_record'=>$this->id_record,
                        'id_column'=>$column->id_column
                    ])->execute();
                else
                    Yii::$app->db->createCommand()->insert('db_collection_value',[
                        'id_column'=>$column->id_column,
                        'id_record'=>$this->id_record,
                        'value'=>(is_array($value))?json_encode($value):$value,
                    ])->execute();

                $dataMongo = array_merge($dataMongo,$this->getMongoDate($value,$column));
                /*$dataMongo['col'.$column->id_column] = ($column->type == CollectionColumn::TYPE_INTEGER)?(float)$value:$value;*/
            }

            $dataMongo['id_record'] = $this->id_record;

            if ($insert)
                $collection->insert($dataMongo);
            else
                $collection->update(['id_record'=>$this->id_record],$dataMongo);

            // Это надо оптимизировать, перенесено под инсерт потомучто не работает при CREATE / MSD
            $dataMongo = [];

            // собираем кастомные колонки
            foreach ($columns as $key => $column)
            {
                if ($column->isCustom())
                {
                    if (empty($recordData))
                        $recordData = $this->getDataAsString(true,true);

                    $dataMongo['col'.$column->id_column] = CollectionColumn::renderCustomValue($column->template,$recordData);
                }
            }

            if (!empty($dataMongo))
                $collection->update(['id_record'=>$this->id_record],$dataMongo);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /*public function updateCustomColumn($column)
    {
        $recordData = $this->getData(true);
    }*/

    public function getDataAsString($keyAsAlias=true,$includeRelation=false)
    {
        $record = \common\components\collection\CollectionQuery::getQuery($this->id_collection)
                    ->select()
                    ->where(['id_record'=>$this->id_record]);

        $columns = $record->columns;

        $record = $record->getArray();

        $output = ['id_record'=>$this->id_record];

        if (!empty($record))
        {
            $record = array_shift($record);

            foreach ($columns as $key => $column)
            {
                $value = $record[$column->id_column];

                if ($includeRelation && $column->isRelation() && !empty($value))
                {
                    if ($column->type == CollectionColumn::TYPE_COLLECTION)
                    {
                        $subrecord = CollectionRecord::findOne(key($value));
                        $output[$column['alias']] = $subrecord->getDataAsString($keyAsAlias,false);
                    }
                    else if ($column->type == CollectionColumn::TYPE_COLLECTIONS)
                    {
                        $output[$column['alias']] = [];

                        foreach ($value as $id_record => $label)
                        {
                            $subrecord = CollectionRecord::findOne($id_record);
                            $output[$column['alias']][] = $subrecord->getDataAsString($keyAsAlias,false);
                        }

                        $output[$column['alias']] = $column->getValueByType($value);
                    }
                }
                else
                {
                    $output[$column['alias']] = $column->getValueByType($value);
                }

            }

            return $output;
        }
        else
            return [];
    }

    public function getData($keyAsAlias=false,$id_columns=[])
    {
        if ($this->isNewRecord)
            return [];

        $record = \common\components\collection\CollectionQuery::getQuery($this->id_collection)
                    ->select()
                    ->where(['id_record'=>$this->id_record]);

        $columns = $record->columns;

        $record = $record->getArray();

        if (!empty($record))
        {
            $record = array_shift($record);

            foreach ($columns as $key => $column)
                $this->loadDataAlias[$column['alias']] = $record[$column->id_column];

            $this->loadData = $record;
        }
        else
            return [];

        /*$rows = (new \yii\db\Query());

        $rows = $rows->select(['dcv.id_column', 'value','id_record','dcc.alias as alias'])
                ->from('db_collection_value as dcv')
                ->join('INNER JOIN', 'db_collection_column as dcc', 'dcc.id_column = dcv.id_column')
                ->where(['id_record'=>$this->id_record]);

        if (!empty($columns))
            $rows->andWhere(['dcv.id_column'=>$id_columns]);*/

        if (!empty($keyAsAlias))
        {
            /*$aliased = [];

            foreach ($columns as $key => $column)
                $aliased[$column['alias']] = $record[$column->id_column];*/

            return $this->loadDataAlias;
        }
        else
            return $record;
    }

    public function getAllMedias()
    {
        $output = [];

        foreach ($this->collection->columns as $key => $column)
        {
            if ($column->type == CollectionColumn::TYPE_FILE || $column->type == CollectionColumn::TYPE_IMAGE)
            {
                $medias = $this->getMedia($column->id_column);

                if (!empty($medias))
                    foreach ($medias as $key => $media)
                        $output[] = $media->getUrl();
            }
        }

        return $output;
    }

    public function getMedia($id_column, $firstElement=false)
    {
        if (empty($this->loadData))
            $this->getData();

        if (!empty($this->loadData[$id_column]))
        {
            //$ids = json_decode($this->loadData[$id_column],true);
            $value = $this->loadData[$id_column];

            if (!empty($value[0]['id']))
            {
                $ids = [];
                foreach ($value as $key => $data) {
                    $ids = $data['id'];
                }

                $value = $ids;
            }

            $medias = Media::find()->where(['id_media'=>$value])->all();

            if (!empty($medias))
                return ($firstElement)?array_shift($medias):$medias;
        }

        return null;
    }

    /*public function afterDelete()
    {
        $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);
        $collection->update(['id_record'=>$this->id_record],['date_delete'=>$this->date_delete]);

        return true;
    }*/

    protected function getLoadData()
    {
        if (empty($this->loadData))
            $this->getData();

        return $this->loadData;
    }

    public function getCollection()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_collection']);
    }

    public function afterDelete()
    {
        $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);
        $collection->remove(['id_record'=>$this->id_record]);

        parent::afterDelete();
    }
}
