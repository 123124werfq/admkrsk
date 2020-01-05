<?php

namespace common\traits;


use common\models\Action;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @method ActiveQuery hasMany($class, array $link)
 * @method ActiveQuery onCondition()
 * @property Action[] $actions
 * @property Action[] $viewActions
 * @property Action[] $viewYearActions
 * @property int $views
 * @property int $viewsYear
 */
trait ActionTrait
{
    /**
     * @return ActiveQuery
     */
    public function getActions()
    {
        return $this->hasMany(Action::class, ['model_id' => self::primaryKey()[0]])
            ->onCondition(['model' => self::class]);
    }

    /**
     * @return ActiveQuery
     */
    public function getViewActions()
    {
        return $this->getActions()
            ->andOnCondition(['action' => Action::ACTION_VIEW]);
    }

    /**
     * @return ActiveQuery
     */
    public function getViewYearActions()
    {
        return $this->getViewActions()
            ->andOnCondition(['>=', 'created_at', mktime(0, 0, 0, 1, 1)]);
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return $this->getViewActions()
            ->count();
    }

    /**
     * @return int
     */
    public function getViewsYear()
    {
        return $this->getViewYearActions()
            ->count();
    }

    /**
     * @param string $action
     * @return bool
     */
    public function createAction($action = Action::ACTION_VIEW)
    {
        /** @var ActiveRecord $this */
        return Action::create($this, $action);
    }
}