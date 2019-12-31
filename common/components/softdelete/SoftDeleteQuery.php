<?php

namespace common\components\softdelete;

use yii\db\ActiveQuery;
use yii\db\Query;
use yii\db\QueryBuilder;

class SoftDeleteQuery extends ActiveQuery
{
    const ALL = 0;
    const ACTIVE = 1;
    const DELETED = 2;

    /**
     * @var string ActiveRecord deleted_at attribute name.
     */
    public $deletedAtAttribute = 'deleted_at';

    /**
     * @var int
     */
    private $_deleted;

    /**
     * @param QueryBuilder $builder
     * @return $this|Query
     */
    public function prepare($builder)
    {
        $query = parent::prepare($builder);

        $alias = array_key_first($query->from);

        if (!is_string($alias)) {
            $alias = $query->from[$alias];
        }

        switch ($this->getDeleted()) {
            case static::ACTIVE:
                $query->andWhere([$alias . '.' . $this->deletedAtAttribute => null]);
                break;
            case static::DELETED:
                $query->andWhere(['not', [$alias . '.' . $this->deletedAtAttribute => null]]);
                break;
            case static::ALL:
            default:
                break;
        }

        return $query;
    }

    /**
     * @return $this
     */
    public function withDeleted()
    {
        $this->_deleted = static::ALL;

        return $this;
    }

    /**
     * @return $this
     */
    public function active()
    {
        $this->_deleted = static::ACTIVE;

        return $this;
    }

    /**
     * @return $this
     */
    public function deleted()
    {
        $this->_deleted = static::DELETED;

        return $this;
    }

    /**
     * @return int
     */
    public function getDeleted()
    {
        if ($this->_deleted === null) {
            $this->_deleted = static::ACTIVE;
        }

        return $this->_deleted;
    }
}
