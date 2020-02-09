<?php

namespace common\models;

use common\behaviors\NestedSetsQueryBehavior;
use common\components\softdelete\SoftDeleteQuery;

class FaqCategoryQuery extends SoftDeleteQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::class,
        ];
    }
}
