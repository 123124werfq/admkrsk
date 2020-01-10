<?php

namespace common\rbac;

use common\models\AuthEntity;
use common\models\Collection;
use common\models\Page;
use common\models\User;
use common\models\UserGroup;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;

class MenuAccessRule extends Rule
{
    public $name = 'MenuAccess';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        if (Yii::$app->authManager->checkAccess($user, str_replace('menu', 'admin', $item->name))) {
            return true;
        }

        if (isset($params['class'])) {
            if ($params['class'] == Collection::class) {
                $accessQuery = AuthEntity::find()
                    ->where([
                        'id_user' => $user,
                        'class' => $params['class'],
                    ]);

                if ($accessQuery->exists()) {
                    return true;
                }

                $accessPartitionQuery = AuthEntity::find()
                    ->where([
                        'id_user' => $user,
                        'class' => Page::class,
                    ]);

                return true;
            }
        }

        return false;
    }
}
