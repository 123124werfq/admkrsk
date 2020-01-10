<?php

namespace common\traits;

use common\models\AuthEntity;
use common\models\UserUserGroup;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

trait AccessTrait
{
    public static function hasAccess()
    {
        return 'AccessTrait';
    }

    public static function getAccessIds()
    {
        $userId = Yii::$app->user->identity->id;

//        $permissionName = 'admin.' . mb_strtolower(StringHelper::basename(self::class));
//
//        if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
//            return null;
//        }

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

        return array_unique(ArrayHelper::merge($userEntityIds, $groupEntityIds));
    }
}
