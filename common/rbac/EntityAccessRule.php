<?php

namespace common\rbac;

use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;

class EntityAccessRule extends Rule
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
        if (isset($params['class']) && method_exists($params['class'], 'hasEntityAccess') && method_exists($params['class'], 'hasAccess')) {
            return $params['class']::hasEntityAccess($params['entity_id'] ?? null);
        }

        return false;
    }
}
