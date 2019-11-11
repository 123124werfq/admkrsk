<?php

namespace backend\modules\log\actions;

use backend\modules\log\models\search\LogSearch;
use Yii;

class IndexAction extends HistoryAction
{
    /**
     * @var string
     */
    public $view = '@backend/modules/log/views/index.php';

    /**
     * @param int $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $searchModel = new LogSearch(['model' => $this->modelClass, 'model_id' => $id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $this->findModelClass($id);

        return $this->controller->render($this->view, [
            'model' => $model,
            'parent' => $this->parent,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
