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
class Log extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log}}';
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
            [['log_id', 'model_id', 'rev', 'previous_id'], 'default', 'value' => null],
            [['log_id', 'model_id', 'rev', 'previous_id'], 'integer'],
            [['data'], 'safe'],
            [['model'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'log_id' => 'ID лога',
            'model' => 'Модель',
            'model_id' => 'ID модели',
            'rev' => 'Ревизия',
            'data' => 'Данные',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'previous_id' => 'Предыдущий лог',
        ];
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
    public function getPrevious()
    {
        return $this->hasOne(Log::class, ['id' => 'previous_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Log::class, ['id' => 'log_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Log::class, ['log_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne($this->model, [(new $this->model)->primaryKey()[0] => 'model_id']);
    }
}
