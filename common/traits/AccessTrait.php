<?php

namespace common\traits;

use common\models\AuthEntity;
use common\models\User;
use common\models\UserUserGroup;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

trait AccessTrait
{
    public static function rbacCacheKey($userId = null)
    {
        static $rbacCacheKey;

        if (!$rbacCacheKey) {
            $userCacheTag = User::rbacCacheTag($userId);
            $className = Inflector::variablize(StringHelper::basename(self::class));

            $rbacCacheKey = "{$userCacheTag->tags[1]}.$className";
        }

        return $rbacCacheKey;
    }

    public static function hasAccessCacheKey($userId = null)
    {
        static $hasAccessCacheKey;

        if (!$hasAccessCacheKey) {
            $hasAccessCacheKey = static::rbacCacheKey($userId) . ".hasAccess";
        }

        return $hasAccessCacheKey;
    }

    public static function hasEntityAccessCacheKey($entity_id, $userId = null)
    {
        return static::rbacCacheKey($userId) . ".hasEntityAccess.$entity_id";
    }

    public static function entityIdsCacheKey($userId = null)
    {
        static $entityIdsCacheKey;

        if (!$entityIdsCacheKey) {
            $entityIdsCacheKey = static::rbacCacheKey($userId) . ".entityIds";
        }

        return $entityIdsCacheKey;
    }

    public static function hasAccess()
    {
        $cacheKey = self::hasAccessCacheKey();

        if (User::rbacCacheIsChanged($cacheKey)) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . Inflector::variablize(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $hasAccess = true;
            } else {
                $hasAccess = !empty(self::getAccessEntityIds());
            }

            Yii::$app->cache->set(
                $cacheKey,
                $hasAccess,
                0,
                User::rbacCacheTag()
            );
        } else {
            $hasAccess = Yii::$app->cache->get($cacheKey);
        }

        return $hasAccess;
    }

    public static function hasEntityAccess($entity_id = null)
    {
        $cacheKey = self::hasEntityAccessCacheKey($entity_id);

        if (User::rbacCacheIsChanged($cacheKey)) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . Inflector::variablize(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $hasEntityAccess = true;
            } elseif ($entity_id) {
                $hasEntityAccess = in_array($entity_id, self::getAccessEntityIds());
            } else {
                $hasEntityAccess = !empty(self::getAccessEntityIds());
            }

            Yii::$app->cache->set(
                $cacheKey,
                $hasEntityAccess,
                0,
                User::rbacCacheTag()
            );
        } else {
            $hasEntityAccess = Yii::$app->cache->get($cacheKey);
        }

        return $hasEntityAccess;
    }

    public static function getAccessEntityIds()
    {
        $cacheKey = self::entityIdsCacheKey();

        if (User::rbacCacheIsChanged($cacheKey)) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . Inflector::variablize(StringHelper::basename(self::class));

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
                $cacheKey,
                $entityIds,
                0,
                User::rbacCacheTag()
            );
        } else {
            $entityIds = Yii::$app->cache->get($cacheKey);
        }

        return $entityIds;
    }
}
