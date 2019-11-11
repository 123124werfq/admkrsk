<?php

namespace common\rbac;

use common\models\AuthEntity;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;

class EntityRule extends Rule
{
    public $name = 'EntityAccess';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $itemPath = explode('.', $item->name);

        if (Yii::$app->authManager->checkAccess($user, 'admin.' . $itemPath[1])) {
            return true;
        }

        if (isset($params['entity_id']) || isset($params['class'])) {
            return AuthEntity::find()->andFilterWhere([
                'user_id' => $user,
                'entity_id' => isset($params['entity_id']) ? (is_callable($params['entity_id']) ? call_user_func($params['entity_id']) : $params['entity_id']) : null,
                'class' => $params['class'] ?? null,
            ])->exists();
        }

        return false;
    }
}
