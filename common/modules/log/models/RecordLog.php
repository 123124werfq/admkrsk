<?php

namespace common\modules\log\models;

use common\models\User;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "{{%log}}".
 *
 * @property int $id
 * @property int $log_id
 * @property string $model
 * @property int $model_id
 * @property int $rev
 * @property array $data
 * @property int $created_at
 * @property int $created_by
 * @property int $previous_id
 *
 * @property User $user
 * @property Log $previous
 * @property Log $parent
 * @property Log $children
 * @property ActiveRecord $entity
 */
class RecordLog extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_record';
    }

    /**
     * @return object|Connection the database connection used by this AR class.
     * @throws InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('logDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_record'], 'integer'],
            [['data'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'id_record' => 'ID записи',
            'data' => 'Данные',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
        ];
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if (is_array($this->data))
                $this->data = json_encode($this->data);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
            'ba' => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(\common\models\CollectionRecord::class, ['id_record' => 'id_record']);
    }
}
