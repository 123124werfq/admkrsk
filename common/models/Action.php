<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Connection;

/**
 * This is the model class for table "action".
 *
 * @property int $id
 * @property string $model
 * @property int $model_id
 * @property string $action
 * @property int $created_by
 * @property int $created_at
 */
class Action extends ActiveRecord
{
    const ACTION_VIEW = 'view';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action';
    }

    /**
     * @return object|Connection
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
            [['model_id'], 'default', 'value' => null],
            [['model_id'], 'integer'],
            [['model', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
            'model_id' => 'Model ID',
            'action' => 'Action',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
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
     * @param ActiveRecord|null $model
     * @param string $action
     * @return bool
     */
    public static function create($model, $action)
    {
        if ($model) {
            $act = new Action();

            if (in_array($action, [Action::ACTION_LOGIN, Action::ACTION_LOGOUT])) {
                $act->model = User::class;
                $act->model_id = Yii::$app->user->id;
            } else {
                $act->model = get_class($model);
                $act->model_id = $model->primaryKey;
            }

            $act->action = $action;

            return $act->save();
        }

        return false;
    }
}
