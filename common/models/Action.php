<?php

namespace common\models;

use common\traits\MetaTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "action".
 * log actions of users.
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
    use MetaTrait;

    const VERBOSE_NAME = 'Действие пользователя';
    const VERBOSE_NAME_PLURAL = 'Действия пользователей';

    const ACTION_VIEW = 'view';
    const ACTION_CREATE = 'create';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_UNDELETE = 'undelete';
    const ACTION_SIGNUP = 'signup';
    const ACTION_SIGNUP_ESIA = 'signup-esia';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGIN_AD = 'login-ad';
    const ACTION_LOGIN_ESIA = 'login-esia';
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
            'action' => 'Действие',
            'created_by' => 'Пользователь',
            'created_at' => 'Дата',
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
     * Log user action.
     *
     * @param ActiveRecord|null $model
     * @param string $action
     * @return bool
     */
    public static function create($model, $action)
    {
        if ($model) {
            $actionModel = new Action();
            if (Yii::$app->user->identity && in_array($action, [Action::ACTION_LOGIN, Action::ACTION_LOGOUT])) {
                $actionModel->model = User::class;
                $actionModel->model_id = Yii::$app->user->identity->id;
            } else {
                $actionModel->model = get_class($model);
                $actionModel->model_id = $model->primaryKey;
            }
            $actionModel->action = $action;
            return $actionModel->save();
        }
        return false;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        $summary = '';

        $model = ($this->model && $this->model_id) ? $this->model::findOne($this->model_id) : null;

        switch ($this->action) {
            case self::ACTION_VIEW:
                $summary = 'Просмотр ' . Html::a($model->pageTitle, ['/' . Inflector::camel2id(StringHelper::basename($this->model)) . '/view', 'id' => $this->model_id], ['target' => '_blank']);
                break;
            case self::ACTION_CREATE:
                $summary = 'Создание ' . Html::a($model->pageTitle, ['/' . Inflector::camel2id(StringHelper::basename($this->model)) . '/view', 'id' => $this->model_id], ['target' => '_blank']);
                break;
            case self::ACTION_UPDATE:
                $summary = 'Редактирование ' . Html::a($model->pageTitle, ['/' . Inflector::camel2id(StringHelper::basename($this->model)) . '/view', 'id' => $this->model_id], ['target' => '_blank']);
                break;
            case self::ACTION_DELETE:
                $summary = 'Удаление ' . Html::a($model->pageTitle, ['/' . Inflector::camel2id(StringHelper::basename($this->model)) . '/view', 'id' => $this->model_id], ['target' => '_blank']);
                break;
            case self::ACTION_UNDELETE:
                $summary = 'Восстановил из архива ' . Html::a($model->pageTitle, ['/' . Inflector::camel2id(StringHelper::basename($this->model)) . '/view', 'id' => $this->model_id], ['target' => '_blank']);
                break;
            case self::ACTION_SIGNUP:
                $summary = 'Регистрация';
                break;
            case self::ACTION_SIGNUP_ESIA:
                $summary = 'Регистрация через ЕСИА';
                break;
            case self::ACTION_LOGIN:
                $summary = 'Авторизация';
                break;
            case self::ACTION_LOGIN_AD:
                $summary = 'Авторизация через Active Directory';
                break;
            case self::ACTION_LOGIN_ESIA:
                $summary = 'Авторизация через ЕСИА';
                break;
            case self::ACTION_LOGOUT:
                $summary = 'Выход';
                break;
        }

        return $summary;
    }
}
