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
    public $data;

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

    public function getLineValue()
    {
        $data = $this->getData();
        return implode(' ', $data);
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

            foreach ($labels as $lkey => $data)
                $mongoLabels[$data['id_record']] = $data['value'];
        }

        return $mongoLabels;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert) && !empty($this->data))
        {
            $this->data_hash = md5($this->data_hash);

            return true;
        }
        else
            return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!empty($this->data))
        {
            $columns = $this->collection->getColumns()->with(['input'])->all();

            // коллекция монги
            $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);

            if ($insert)
            {
                unset($this->data['id_record']);

                // запись в postgree
                $insertData = [];
                $dataMongo = [];

                foreach ($columns as $key => $column)
                {
                    if (isset($this->data[$column->id_column]))
                    {
                        $value = $this->data[$column->id_column];

                        $insertData[] = [
                            'id_column'=>$column->id_column,
                            'id_record'=>$this->id_record,
                            'value'=>(is_array($value))?json_encode($value):$value,
                        ];

                        if (!empty($column->input->id_collection) && !empty($column->input->id_collection_column))
                        {
                            if (is_numeric($value))
                                $ids = [$value];
                            elseif (is_array($value))
                                $ids = $value; // json_decode($value,true);*/
                            else if (is_string($value))
                                $ids = json_decode($value,true);

                            $ids = $ids??[];

                            $mongoLabels = $this->getLabelsByID($ids,$column);

                            foreach ($ids as $idskey => $id)
                                $ids[$idskey] = (int)$id;

                            $dataMongo['col'.$column->id_column] = $ids;
                            $dataMongo['col'.$column->id_column.'_search'] = implode(';', $mongoLabels);
                        }
                        else
                            $dataMongo['col'.$column->id_column] = (is_numeric($value)&& strpos($value, '.')==false)?(float)$value:$value;
                    }
                }

                Yii::$app->db->createCommand()->batchInsert('db_collection_value',['id_column','id_record','value'],$insertData)->execute();

            }
            else
            {
                $dataMongo = [];

                foreach ($columns as $key => $column)
                {
                    $updateData = null;

                    if (isset($this->data[$column->alias]))
                        $updateData = $this->data[$column->alias];
                    elseif (isset($this->data[$column->id_column]))
                        $updateData = $this->data[$column->id_column];

                    if ($updateData!==null)
                    {
                        $count = Yii::$app->db->createCommand("SELECT count(*) FROM db_collection_value WHERE id_record = $this->id_record AND
                            id_column = $column->id_column")->queryScalar();

                        if ($count>0)
                            Yii::$app->db->createCommand()->update('db_collection_value',
                                ['value'=>is_array($updateData)?json_encode($updateData):$updateData],[
                                'id_record'=>$this->id_record,
                                'id_column'=>$column->id_column
                            ])->execute();
                        else
                            Yii::$app->db->createCommand()->insert('db_collection_value',[
                                'id_record'=>$this->id_record,
                                'id_column'=>$column->id_column,
                                'value'=>is_array($updateData)?json_encode($updateData):$updateData
                            ])->execute();

                        if (!empty($column->input->id_collection) && !empty($column->input->id_collection_column))
                        {
                            if (is_numeric($updateData))
                                $ids = [$updateData];
                            else if (is_array($updateData))
                                $ids = $updateData;
                            else if (is_string($updateData))
                                $ids = json_decode($updateData,true);

                            $ids = $ids??[];
                            $mongoLabels = $this->getLabelsByID($ids,$column);

                            foreach ($ids as $idskey => $id)
                                $ids[$idskey] = (int)$id;

                            $dataMongo['col'.$column->id_column] = $ids;
                            $dataMongo['col'.$column->id_column.'_search'] = implode(';', $mongoLabels);
                        }
                        else
                            $dataMongo['col'.$column->id_column] = (is_numeric($updateData) && strpos($updateData, '.')==false)?(float)$updateData:$updateData;
                    }
                }
            }

            // собираем кастомные колонки
            foreach ($columns as $key => $column)
            {
                if ($column->isCustom())
                {
                    if (empty($recordData))
                        $recordData = $this->getData(true);

                    $dataMongo['col'.$column->id_column] = CollectionColumn::renderCustomValue($column->template,$recordData);
                }
            }

            $dataMongo['id_record'] = $this->id_record;

            if ($insert)
                $collection->insert($dataMongo);
            else
                $collection->update(['id_record'=>$this->id_record],$dataMongo);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /*public function updateCustomColumn($column)
    {
        $recordData = $this->getData(true);

    }*/

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
            $medias = Media::find()->where(['id_media'=>$this->loadData[$id_column]])->all();

            if (!empty($medias))
                return ($firstElement)?array_shift($medias):$medias;
        }

        return null;
    }

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
