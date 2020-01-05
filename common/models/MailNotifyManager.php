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
     * @param int $entityId
     * @param string $class
     * @param array|int $newUsersIds
     * @return User[]|null
     */
    public static function getUsersForSendNotify($entityId, $class, $newUsersIds)
    {
        if (!$newUsersIds) {
            static::deleteAll([
                'entity_id' => $entityId,
                'class' => $class,
            ]);
            return null;
        }
        $existUsersNotify = static::getUsersNotifyByEntityAndClass($entityId, $class);
        if ($existUsersNotify) {
            $normalizeUsersNotify = static::normalizeNotify($entityId, $class, $newUsersIds, $existUsersNotify);
            $validUsers = [];
            foreach ($normalizeUsersNotify as $user) {
                if (static::isNeedNotifyUser($entityId, $class, $user)) {
                    $validUsers[] = $user;
                }
            }
            return $validUsers;
        } else {
            static::factoryCreateUsersNotify($entityId, $class, $newUsersIds);
            return static::getUsersNotifyByEntityAndClass($entityId, $class);
        }
    }

    /**
     * Create user notification if not exist,
     * and delete old.
     *
     * @param $entityId
     * @param $class
     * @param $newUsersIds
     * @param $usersNotify
     * @return User[]|null
     */
    private static function normalizeNotify($entityId, $class, $newUsersIds, $usersNotify)
    {
        if (!is_array($newUsersIds)) {
            $newUsersIds = [$newUsersIds];
        }

        $oldUsersIds = [];
        /** @var User[] $usersNotify */
        foreach ($usersNotify as $userNotify) {
            $oldUsersIds[] = intval($userNotify->id);
        }
        $deleteUsersIds = array_diff($oldUsersIds, $newUsersIds);
        if ($deleteUsersIds) {
            static::deleteAll([
                'entity_id' => $entityId,
                'class' => $class,
                'user_id' => $deleteUsersIds,
            ]);
        }
        $createUsersIds = array_diff($newUsersIds, $oldUsersIds);
        if ($createUsersIds) {
            static::factoryCreateUsersNotify($entityId, $class, $createUsersIds);
        }
        return static::getUsersNotifyByEntityAndClass($entityId, $class);
    }

    /**
     * @param int $entityId
     * @param string $class
     * @return User[]|null
     */
    private static function getUsersNotifyByEntityAndClass($entityId, $class)
    {
        $notifyUsersIds = static::find()->select('user_id')
            ->where([
                'entity_id' => $entityId,
                'class' => $class,
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
     * @param $entityId
     * @param $class
     * @param User $user
     * @return bool
     */
    private static function isNeedNotifyUser($entityId, $class, $user)
    {
        if (static::isNotify($class, $user)) {
            if (static::isRepeatNotify($entityId, $class, $user)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Time rule  (2)
     *
     * @param int $entityId
     * @param string $class
     * @param User $user
     * @return bool
     */
    private static function isRepeatNotify($entityId, $class, $user)
    {
        /** @var NotifyMessage $lastUserEntityMessage */
        $lastUserEntityMessage = NotifyMessage::find()
            ->where([
                'entity_id' => $entityId,
                'class' => $class,
                'user_id' => $user->id,
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(1)
            ->one();

        if (!$lastUserEntityMessage) {
            return true;
        }
        /** @var Notify $notifyRuleByClass */
        $notifyRuleByClass = Notify::getNotifyRuleByClass($class);

        $timeOfLastMessage = $lastUserEntityMessage->created_at;
        $currentTime = time();
        $lastRepeatDate = strtotime("+ {$notifyRuleByClass->repeatNotifyTime}", $timeOfLastMessage);

        if ($currentTime > $timeOfLastMessage && $currentTime < $lastRepeatDate) {
            return false;
        }
        return true;
    }

    /**
     * Time rule  (1)
     *
     * @param string $class
     * @param User $user
     * @return bool
     */
    private static function isNotify($class, $user)
    {
        /** @var NotifyMessage $lastUserMessage */
        $lastUserMessage = NotifyMessage::find()
            ->where([
                'class' => $class,
                'user_id' => $user->id,
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(1)
            ->one();

        if (!$lastUserMessage) {
            return true;
        }
        /** @var Notify $notifyRuleByClass */
        $notifyRuleByClass = Notify::getNotifyRuleByClass($class);

        $timeOfLastMessage = $lastUserMessage->created_at;
        $currentTime = time();
        $lastMainTime = strtotime("+ {$notifyRuleByClass->mainNotifyTime}", $timeOfLastMessage);

        if ($currentTime > $timeOfLastMessage && $currentTime < $lastMainTime) {
            return false;
        }
        return true;
    }

    /**
     * Create users notify
     *
     * @param int $entityId
     * @param string $class
     * @param array $usersIds
     */
    private static function factoryCreateUsersNotify($entityId, $class, $usersIds)
    {
        if (!is_array($usersIds)) {
            static::createUserNotify($entityId, $class, $usersIds);
        } else {
            foreach ($usersIds as $userId) {
                static::createUserNotify($entityId, $class, $userId);
            }
        }
    }

    /**
     * @param int $entityId
     * @param string $class
     * @param int $userId
     */
    private static function createUserNotify($entityId, $class, $userId)
    {
        $notify = new static();
        $notify->entity_id = $entityId;
        $notify->class = $class;
        $notify->user_id = $userId;
        $notify->save();
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
