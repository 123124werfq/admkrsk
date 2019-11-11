<?php

namespace common\models;

use Yii;

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
 */
class CollectionRecord extends \yii\db\ActiveRecord
{
    public $data;
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
            [['id_collection', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_collection', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
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
            'id_collection' => 'Id Collection',
            'ord' => 'Ord',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /*public function updateRecord($data)
    {
        if (!empty($data))
        {
            $update = [];
            foreach ($this->collection->columns as $key => $column)
            {
                if (isset($data[$column->alias]))
                    Yii::$app->db->createCommand()->update('db_collection_value',['value'=>$data[$column->alias]],['id_record'=>$this->id_record,'id_column'=>$column->id_column])->execute();
            }

            return $this;
        }
    }*/

    public function getLineValue()
    {
        $data = $this->getData();
        return implode(' ', $data);
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (!empty($this->data))
        {
            if ($insert)
            {
                unset($this->data['id_record']);

                // запись в postgree
                $insertData = [];

                foreach ($this->data as $key => $value)
                {
                    $insertData[] = [
                        'id_column'=>$key,
                        'id_record'=>$this->id_record,
                        'value'=>$value
                    ];
                }

                Yii::$app->db->createCommand()->batchInsert('db_collection_value',['id_column','id_record','value'],$insertData)->execute();

                // запись в mongo
                $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);
                $this->data['id_record'] = $this->id_record;
                $collection->insert($this->data);
            }
            else
            {
                $update = [];

                foreach ($this->collection->columns as $key => $column)
                {
                    $updateData = null;

                    if (isset($this->data[$column->alias]))
                        $updateData = $this->data[$column->alias];
                    elseif (isset($this->data[$column->id_column]))
                        $updateData = $this->data[$column->id_column];

                    if ($updateData!==null)
                    {
                        Yii::$app->db->createCommand()->update('db_collection_value',['value'=>$updateData],['id_record'=>$this->id_record,'id_column'=>$column->id_column])->execute();
                    }
                }

                $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);
                $this->data['id_record'] = $this->id_record;
                $collection->update(['id_record'=>$this->id_record],$this->data);
            }
        }
    }

    public function getData($id_columns=[])
    {
        if ($this->isNewRecord)
            return [];

        $rows = (new \yii\db\Query());

        $rows = $rows->select(['dcv.id_column', 'value','id_record'])
                ->from('db_collection_value as dcv')
                ->where(['id_record'=>$this->id_record]);

        if (!empty($columns))
            $rows->andWhere(['dcv.id_column'=>$id_columns]);

        $output = [];

        foreach ($rows->all() as $key => $data) {
            $output[$data['id_column']] = $data['value'];
        }

        return $output;
    }

    public function getCollection()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_collection']);
    }
}
