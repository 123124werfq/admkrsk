<?php

namespace common\traits;

use common\models\Action;
use common\models\Statistic;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @method ActiveQuery hasMany($class, array $link)
 * @method ActiveQuery onCondition()
 * @property Statistic[] $viewActions
 * @property Statistic[] $viewYearActions
 * @property int $views
 * @property int $viewsYear
 */
trait ActionTrait
{
    /**
     * @return ActiveQuery
     */
    public function getViewActions()
    {
        return $this->hasMany(Statistic::class, ['model_id' => self::primaryKey()[0]])
            ->onCondition(['model' => self::class]);
    }

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getViewYearActions()
    {
        return $this->getViewActions()
            ->andOnCondition(['year' => (int) Yii::$app->formatter->asDate(time(), 'Y')]);
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return (int)  $this->getViewActions()
            ->select('views')
            ->scalar();
    }

    /**
     * @return int
     * @throws \yii\base\InvalidConfigException
     */
    public function getViewsYear()
    {
        return (int) $this->getViewYearActions()
            ->select('views')
            ->scalar();
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