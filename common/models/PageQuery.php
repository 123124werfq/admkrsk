<?php

namespace common\models;

use common\behaviors\NestedSetsQueryBehavior;
use common\components\softdelete\SoftDeleteQuery;

class PageQuery extends SoftDeleteQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::class,
        ];
    }
}
