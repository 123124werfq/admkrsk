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

    public $loadData = [];

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
                        $count = Yii::$app->db->createCommand("SELECT count(*) FROM db_collection_value WHERE id_record=$this->id_record AND
                            id_column=$column->id_column")->queryScalar();

                        if ($count>0)
                            Yii::$app->db->createCommand()->update('db_collection_value',['value'=>$updateData],[
                                'id_record'=>$this->id_record,
                                'id_column'=>$column->id_column
                            ])->execute();
                        else
                            Yii::$app->db->createCommand()->insert('db_collection_value',[
                                'id_record'=>$this->id_record,
                                'id_column'=>$column->id_column,
                                'value'=>$updateData
                            ])->execute();
                    }
                }

                $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);
                $this->data['id_record'] = $this->id_record;
                $collection->update(['id_record'=>$this->id_record],$this->data);
            }
        }
    }

    public function getData($keyAsAlias=false,$id_columns=[])
    {
        if ($this->isNewRecord)
            return [];

        $rows = (new \yii\db\Query());

        $rows = $rows->select(['dcv.id_column', 'value','id_record','dcc.alias as alias'])
                ->from('db_collection_value as dcv')
                ->join('INNER JOIN', 'db_collection_column as dcc', 'dcc.id_column = dcv.id_column')
                ->where(['id_record'=>$this->id_record]);

        if (!empty($columns))
            $rows->andWhere(['dcv.id_column'=>$id_columns]);

        $output = [];

        foreach ($rows->all() as $key => $data)
            $output[$keyAsAlias?$data['alias']:$data['id_column']] = $data['value'];

        $this->loadData = $output;

        return $output;
    }

    public function getAllMedias()
    {
        $output = [];

        foreach ($this->collection->columns as $key => $column)
        {
            if ($column->type == CollectionColumn::TYPE_FILE || $column->type == CollectionColumn::TYPE_IMAGE)
            {
                $medias = $this->getMedia($column->id_column);
                if(!empty($medias))
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
            $ids = json_decode($this->loadData[$id_column],true);

            $medias = Media::find()->where(['id_media'=>$ids])->all();

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
