<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $user_id
 * @property int $entity_id
 * @property string $class
 * @property int $created_at
 */
class MailNotifyManager extends ActiveRecord
{
    /**
     * @var int|array
     */
    public $usersNotify;

    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * Model attribute
     *
     * @var integer
     */
    public $timeRule;

    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var int|string
     */
    private $modelId;

    /**
     * Will admin receive notify
     *
     * @param string|int $entityId
     * @param string $class
     * @return bool
     */
    public static function isAdminNotify($entityId, $class)
    {
        if (Yii::$app->user->can('admin.collection')) {
            $notify = static::find()
                ->where([
                    'user_id' => Yii::$app->user->id,
                    'entity_id' => intval($entityId),
                    'class' => $class,
                ])
                ->limit(1)
                ->one();
            if ($notify) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get users which validated time rules.
     *
     * @return User[]|null
     */
    public function getNotifyUsers()
    {
        $this->modelClass = get_class($this->model);
        $this->modelId = $this->model->primaryKey;
        if (!$this->usersNotify) {
            static::deleteAll([
                'entity_id' => $this->modelId,
                'class' => $this->modelClass,
            ]);
            return null;
        }
        $existUsersNotify = $this->getExistUsersNotify();
        if ($existUsersNotify) {
            $normalizeUsersNotify = $this->normalizeNotify($existUsersNotify);
            $validUsers = [];
            foreach ($normalizeUsersNotify as $user) {
                if ($this->isNeedNotifyUser($user)) {
                    $validUsers[] = $user;
                }
            }
            return $validUsers;
        } else {
            $this->factoryCreateUsersNotify();
            return $this->getExistUsersNotify();
        }
    }

    /**
     * Create user notification if not exist,
     * and delete old.
     *
     * @param $usersNotify
     * @return User[]|null
     */
    private function normalizeNotify($usersNotify)
    {
        if (!is_array($this->usersNotify)) {
            $this->usersNotify = [$this->usersNotify];
        }

        $oldUsersIds = [];
        /** @var User[] $usersNotify */
        foreach ($usersNotify as $userNotify) {
            $oldUsersIds[] = intval($userNotify->id);
        }
        $deleteUsersIds = array_diff($oldUsersIds, $this->usersNotify);
        if ($deleteUsersIds) {
            static::deleteAll([
                'entity_id' => $this->modelId,
                'class' => $this->modelClass,
                'user_id' => $deleteUsersIds,
            ]);
        }
        $createUsersIds = array_diff($this->usersNotify, $oldUsersIds);
        if ($createUsersIds) {
            $this->factoryCreateUsersNotify();
        }
        return $this->getExistUsersNotify();
    }

    /**
     * @return User[]|null
     */
    private function getExistUsersNotify()
    {
        $notifyUsersIds = static::find()->select('user_id')
            ->where([
                'entity_id' => $this->modelId,
                'class' => $this->modelClass,
            ])
            ->asArray()
            ->column();
        if ($notifyUsersIds) {
            return User::find()->where(['id' => $notifyUsersIds])->all();
        }
        return null;
    }

    /**
     * Validated time rules
     *
     * @param User $user
     * @return bool
     */
    private function isNeedNotifyUser($user)
    {
        if ($this->isNotify($user)) {
            return true;
        }
        return false;
    }

    /**
     * Time rule
     *
     * @param User $user
     * @return bool
     */
    private function isNotify($user)
    {
        if (!$this->timeRule || $this->timeRule === 0) {
            return false;
        }
        /** @var NotifyMessage $lastUserEntityMessage */
        $lastUserEntityMessage = NotifyMessage::find()
            ->where([
                'entity_id' => $this->modelId,
                'class' => $this->modelClass,
                'user_id' => $user->id,
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(1)
            ->one();

        if (!$lastUserEntityMessage) {
            return true;
        }
        $notifyRuleByClass = static::mapTime($this->timeRule);

        $timeOfLastMessage = $lastUserEntityMessage->created_at;
        $currentTime = time();
        $lastRepeatDate = strtotime("+ {$notifyRuleByClass}", $timeOfLastMessage);

        if ($currentTime > $timeOfLastMessage && $currentTime < $lastRepeatDate) {
            return false;
        }
        return true;
    }

    /**
     * Create users notify
     */
    private function factoryCreateUsersNotify()
    {
        if (!is_array($this->usersNotify)) {
            $this->createUserNotify($this->usersNotify);
        } else {
            foreach ($this->usersNotify as $userId) {
                $this->createUserNotify($userId);
            }
        }
    }

    private function createUserNotify($userId)
    {
        $notify = new static();
        $notify->entity_id = $this->modelId;
        $notify->class = $this->modelClass;
        $notify->user_id = $userId;
        $notify->save();
    }

    /**
     * Map receive time from frontend
     *
     * @param int $time
     * @return string|null
     */
    private static function mapTime($time)
    {
        switch ($time) {
            case 0:
                return '0 minutes';
            case 1:
                return '30 minutes';
            case 2:
                return '1 hour';
            case 3:
                return '3 hour';
            default:
                return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notify_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'entity_id'], 'integer'],
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
        ];
    }
}