<?php

namespace backend\modules\log\actions;

use backend\modules\log\models\Log;
use common\models\Collection;
use common\models\Page;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

/**
 * @property string $classLabel
 */
class HistoryAction extends Action
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var string
     */
    public $view;

    /**
     * @var array
     */
    public $parent;

    /**
     * @var array
     */
    public $classLabels = [
        Page::class => 'Разделы',
        Collection::class => 'Списки',
    ];

    /**
     * @param int $id
     * @return Log
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param int $id
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModelClass($id)
    {
        if (($model = $this->modelClass::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @return string|null
     */
    public function getClassLabel()
    {
        return $this->classLabels[$this->modelClass] ?? null;
    }
}
