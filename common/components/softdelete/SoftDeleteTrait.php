<?php

namespace common\components\softdelete;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\ModelEvent;
use yii\db\ActiveQueryInterface;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;

trait SoftDeleteTrait
{
    /**
     * @var string ActiveRecord deleted_at attribute name.
     */
    protected static $deletedAtAttribute = 'deleted_at';

    /**
     * @var string ActiveRecord deleted_by attribute name.
     */
    protected static $deletedByAttribute = 'deleted_by';

    /**
     * @var bool
     */
    private $forceDelete = false;

    /**
     * @return object|SoftDeleteQuery|SoftDeleteTrait
     */
    public static function find()
    {
        /** @var SoftDeleteQuery $query */
        $query =  Yii::createObject(SoftDeleteQuery::class, [get_called_class()]);

        return $query->active();
    }

    /**
     * @return object|SoftDeleteQuery|SoftDeleteTrait
     */
    public static function findDeleted()
    {
        /** @var SoftDeleteQuery $query */
        $query =  Yii::createObject(SoftDeleteQuery::class, [get_called_class()]);

        return $query->deleted();
    }

    /**
     * @return object|SoftDeleteQuery|SoftDeleteTrait
     */
    public static function findWithDeleted()
    {
        /** @var SoftDeleteQuery $query */
        $query =  Yii::createObject(SoftDeleteQuery::class, [get_called_class()]);

        return $query->withDeleted();
    }

    /**
     * @param mixed $condition
     * @return null|static|object|SoftDeleteQuery|SoftDeleteTrait
     */
    public static function findOneDeleted($condition)
    {
        return static::findByCondition($condition, static::findDeleted())->one();
    }

    /**
     * @param mixed $condition
     * @return null|static|object|SoftDeleteQuery|SoftDeleteTrait
     */
    public static function findOneWithDeleted($condition)
    {
        return static::findByCondition($condition, static::findWithDeleted())->one();
    }

    /**
     * @param mixed $condition
     * @return static[]|object[]|SoftDeleteQuery[]|SoftDeleteTrait[]
     */
    public static function findAllDeleted($condition)
    {
        return static::findByCondition($condition, static::findDeleted())->all();
    }

    /**
     * @param mixed $condition
     * @return static[]|object[]|SoftDeleteQuery[]|SoftDeleteTrait[]
     */
    public static function findAllWithDeleted($condition)
    {
        return static::findByCondition($condition, static::findWithDeleted())->all();
    }

    /**
     * Finds ActiveRecord instance(s) by the given condition.
     * This method is internally called by [[findOne()]] and [[findAll()]].
     *
     * @param mixed $condition please refer to [[findOne()]] for the explanation of this parameter
     * @param ActiveQueryInterface $query
     * @return ActiveQueryInterface the newly created [[SoftDeleteQueryInterface|ActiveQuery]] instance.
     * @throws InvalidConfigException if there is no primary key defined
     * @internal
     */
    protected static function findByCondition($condition, $query = null)
    {
        if ($query === null) {
            $query = static::find();
        }

        if (!ArrayHelper::isAssociative($condition)) {
            // query by primary key
            $primaryKey = static::primaryKey();

            if (isset($primaryKey[0])) {
                $condition = [$primaryKey[0] => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $query->andWhere($condition);
    }

    /**
     * @return bool
     */
    public function forceDelete()
    {
        $this->forceDelete = true;
        $result = $this->delete();
        $this->forceDelete = false;

        return $result;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return !empty($this->getOldAttribute(static::$deletedAtAttribute));
    }

    /**
     * @return bool
     */
    public function beforeSoftDelete()
    {
        $event = new ModelEvent();

        $this->trigger(SoftDelete::EVENT_BEFORE_SOFT_DELETE, $event);

        return $event->isValid;
    }

    /**
     * @return bool
     */
    public function afterSoftDelete()
    {
        $event = new ModelEvent();

        $this->trigger(SoftDelete::EVENT_AFTER_SOFT_DELETE, $event);

        return $event->isValid;
    }

    /**
     * @return bool
     */
    public function beforeForceDelete()
    {
        $event = new ModelEvent();

        $this->trigger(SoftDelete::EVENT_BEFORE_FORCE_DELETE, $event);

        return $event->isValid;
    }

    /**
     * @return bool
     */
    public function afterForceDelete()
    {
        $event = new ModelEvent();

        $this->trigger(SoftDelete::EVENT_AFTER_FORCE_DELETE, $event);

        return $event->isValid;
    }

    /**
     * @return bool
     */
    public function beforeRestore()
    {
        $event = new ModelEvent();

        $this->trigger(SoftDelete::EVENT_BEFORE_RESTORE, $event);

        return $event->isValid;
    }

    /**
     * @return bool
     */
    public function afterRestore()
    {
        $event = new ModelEvent();

        $this->trigger(SoftDelete::EVENT_AFTER_RESTORE, $event);

        return $event->isValid;
    }

    /**
     * @return bool
     * @throws StaleObjectException
     */
    protected function deleteInternal()
    {
        if (!$this->beforeDelete()) {
            return false;
        }

        if ($this->forceDelete) {
            $this->beforeForceDelete();

            // we do not check the return value of deleteAll() because it's possible
            // the record is already deleted in the database and thus the method will return 0
            $condition = $this->getOldPrimaryKey(true);
            $lock = $this->optimisticLock();

            if ($lock !== null) {
                $condition[$lock] = $this->$lock;
            }

            $result = static::deleteAll($condition);

            if ($lock !== null && !$result) {
                throw new StaleObjectException('The object being deleted is outdated.');
            }

            $this->setOldAttributes(null);

            $this->afterForceDelete();
        } else {
            $result = $this->softDeleteInternal();
        }

        $this->afterDelete();

        return $result;
    }

    /**
     * @return bool
     * @throws StaleObjectException
     */
    protected function softDeleteInternal()
    {
        if (!$this->beforeSoftDelete()) {
            return false;
        }

        $values = [
            static::$deletedAtAttribute => time(),
            static::$deletedByAttribute => Yii::$app->user->identity->id ?? null,
        ];

        if ($this->isDeleted()) {
            $this->afterSoftDelete();

            return true;
        }

        $condition = $this->getOldPrimaryKey(true);
        $lock = $this->optimisticLock();

        if ($lock !== null) {
            $values[$lock] = $this->$lock + 1;
            $condition[$lock] = $this->$lock;
        }

        // We do not check the return value of updateAll() because it's possible
        // that the UPDATE statement doesn't change anything and thus returns 0.
        $result = static::updateAll($values, $condition);

        if ($lock !== null && !$result) {
            throw new StaleObjectException('The object being updated is outdated.');
        }

        if (isset($values[$lock])) {
            $this->$lock = $values[$lock];
        }

        $this->afterSoftDelete();

        return $result;
    }

    /**
     * @return bool
     */
    public function restore()
    {
        if (!$this->beforeRestore()) {
            return false;
        }

        if (empty($this->getOldAttribute(static::$deletedAtAttribute))) {
            $this->afterRestore();
            return true;
        }

        $this->{static::$deletedAtAttribute} = null;
        $this->{static::$deletedByAttribute} = null;

        $result = $this->save(false);

        $this->afterRestore();

        return $result;
    }
}
