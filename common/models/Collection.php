<?php

namespace common\models;

use common\behaviors\UserAccessControlBehavior;
use common\components\yiinput\RelationBehavior;
use common\modules\log\behaviors\LogBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "db_collection".
 *
 * @property int $id_collection
 * @property string $name
 * @property string $alias
 * @property string $is_dictionary
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property array $access_user_ids
 *
 * @property CollectionColumn[] $columns
 */
class Collection extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Список';
    const VERBOSE_NAME_PLURAL = 'Списки';
    const TITLE_ATTRIBUTE = 'name';

    public $access_user_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['id_parent_collection'], 'integer'],
            [['filter', 'options'], 'safe'],
            /*['access_user_ids', 'each', 'rule', 'is_dictionary' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_collection' => '#',
            'name' => 'Название',
            'alias' => 'Алиас',
            'id_form' => 'Форма редактирования',
            'id_parent_collection' => 'Это справочник',
            'is_dictionary' => 'Это справочник',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Обновил',
            'deleted_at' => 'Удалено',
            'deleted_by' => 'Удалил',
            'access_user_ids' => 'Доступ',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
            'ac' => [
                'class' => UserAccessControlBehavior::class,
                'permission' => 'backend.collection',
            ],
            'yiinput' => [
                'class' => RelationBehavior::class,
                'relations'=> [
                    'columns'=>[
                        'modelname'=> 'CollectionColumn',
                        'added'=>true,
                    ],
                ]
            ]
        ];
    }

    /*public function insertRecord($data)
    {
        $model = new CollectionRecord;
        $model->id_collection = $this->id_collection;

        if (!empty($data) && $model->save())
        {
            $insert = [];
            foreach ($this->columns as $key => $column)
            {
                if (isset($data[$column->alias]))
                    $insert[] = [
                        'id_column'=>$column->id_column,
                        'id_record'=>$model->id_record,
                        'value'=>$data[$column->alias]
                    ];
            }

            Yii::$app->db->createCommand()->batchInsert('db_collection_value',['id_column','id_record','value'], $insert)->execute();

            return $model;
        }
        return false;
    }*/

    /*public function updateRecord($id_record,$data)
    {
        $model = CollectionRecord::find($id_record);
        return $model->updateRecord();

        $model->id_collection = $this->id_collection;

        if (!empty($data) && $model->save())
        {
            $insert = [];
            foreach ($this->columns as $key => $column)
            {
                if (isset($data[$column->alias]))
                    $insert[] = [
                        'id_column'=>$column->id_column,
                        'id_record'=>$model->id_record,
                        'value'=>$data[$column->alias]
                    ];
            }

            if (!empty($insert))
            {
                Yii::$app->db->createCommand()->batchInsert('db_collection_value',['id_column','id_record','value'], $insert)->execute();

                return $model;
            }

            return false;

        }

        return false;
    }*/

    public function getParent()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_parent_collection']);
    }

    public function getItems()
    {
        return $this->hasMany(CollectionRecord::class, ['id_collection' => 'id_collection'])->orderBy('ord ASC');
    }

    /**
     * @return ActiveQuery
     */
    public function getColumns()
    {
        if (empty($this->id_parent_collection))
            return $this->hasMany(CollectionColumn::class, ['id_collection' => 'id_collection'])->orderBy('ord ASC');
        else
            return $this->hasMany(CollectionColumn::class, ['id_collection' => 'id_parent_collection'])->orderBy('ord ASC');
    }

    public function getArray()
    {
        $data = $this->getData();

        $output = [];

        foreach ($data as $key => $row)
        {
            $output[$key] = implode(' ', $row);
        }

        return $output;
    }

    public function getData($id_columns=[], $keyAsAlias=false)
    {
        if (!empty($this->id_parent_collection))
            $id_collection = $this->id_parent_collection;
        else
            $id_collection = $this->id_collection;

        $query = \common\components\collection\CollectionQuery::getQuery($id_collection);

        if (!empty($this->options))
        {
            $options = json_decode($this->options,true);

            if (!empty($options['filters']))
            {
                foreach ($options['filters'] as $key => $filter)
                {
                    $where = [$filter['operator'],$filter['id_column'],$filter['value']];
                    if ($key==0)
                        $query->where($where);
                    else
                        $query->andWhere($where);
                }
            }
        }

        $query->keyAsAlias = $keyAsAlias;

        return $query->select($id_columns)->getArray();
    }

    public function getDataQuery()
    {
        if (!empty($this->id_parent_collection))
            $id_collection = $this->id_parent_collection;
        else
            $id_collection = $this->id_collection;

        $query = \common\components\collection\CollectionQuery::getQuery($id_collection);

        if (!empty($this->options))
        {
            $options = json_decode($this->options,true);

            if (!empty($options['filters']))
            {
                foreach ($options['filters'] as $key => $filter)
                {
                    $where = [$filter['operator'],$filter['id_column'],$filter['value']];
                    if ($key==0)
                        $query->where($where);
                    else
                        $query->andWhere($where);
                }
            }
        }

        return $query;
    }

    public function getViewFilters()
    {
        $options = json_decode($this->options,true);

        if (isset($options['filters']))
            return $options['filters'];

        return [];
    }

    public function getViewColumns()
    {
        $options = json_decode($this->options,true);

        if (isset($options['columns']))
            return $options['columns'];

        $options = [];

        foreach ($this->parent->columns as $key => $column)
        {
            $options[] = [
                'id_column'=>$column->id_column,
                'value'=>'',
            ];
        }

        return $options;
    }

}
