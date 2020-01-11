<?php

namespace common\traits;

use common\models\AuthEntity;
use common\models\User;
use common\models\UserUserGroup;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

trait AccessTrait
{
    public static function rbacCacheKey()
    {
        static $rbacCacheKey;

        if (!$rbacCacheKey) {
            $userCacheTag = User::rbacCacheTag();
            $className = mb_strtolower(StringHelper::basename(self::class));

            $rbacCacheKey = "$userCacheTag.$className";
        }

        return $rbacCacheKey;
    }

    public static function hasAccessCacheKey()
    {
        static $hasAccessCacheKey;

        if (!$hasAccessCacheKey) {
            $hasAccessCacheKey = static::rbacCacheKey() . ".hasAccess";
        }

        return $hasAccessCacheKey;
    }

    public static function entityIdsCacheKey()
    {
        static $entityIdsCacheKey;

        if (!$entityIdsCacheKey) {
            $entityIdsCacheKey = static::rbacCacheKey() . ".entityIds";
        }

        return $entityIdsCacheKey;
    }

    public static function hasAccess()
    {
        if (!Yii::$app->cache->exists(self::hasAccessCacheKey())) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . mb_strtolower(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $hasAccess = true;
            } else {
                $hasAccess = !empty(self::getAccessEntityIds());
            }

            Yii::$app->cache->set(
                self::hasAccessCacheKey(),
                $hasAccess,
                0,
                new TagDependency(['tags' => User::rbacCacheTag()])
            );
        } else {
            $hasAccess = Yii::$app->cache->get(self::hasAccessCacheKey());
        }

        return $hasAccess;
    }

    public static function getAccessEntityIds()
    {
        if (!Yii::$app->cache->exists(self::entityIdsCacheKey())) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . mb_strtolower(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $entityIds = null;
            } else {
                $userEntityIds = AuthEntity::find()
                    ->select('entity_id')
                    ->andWhere([
                        'id_user' => $userId,
                        'class' => self::class,
                    ])
                    ->column();

                $groupEntityIds = AuthEntity::find()
                    ->select('entity_id')
                    ->andWhere([
                        'id_user_group' => UserUserGroup::find()
                            ->select('id_user_group')
                            ->andWhere(['id_user' => $userId]),
                        'class' => self::class,
                    ])
                    ->column();

                $entityIds = array_unique(ArrayHelper::merge($userEntityIds, $groupEntityIds));
            }

            Yii::$app->cache->set(
                self::entityIdsCacheKey(),
                $entityIds,
                0,
                new TagDependency(['tags' => User::rbacCacheTag()])
            );
        } else {
            $entityIds = Yii::$app->cache->get(self::entityIdsCacheKey());
        }

        return $entityIds;
    }
}
