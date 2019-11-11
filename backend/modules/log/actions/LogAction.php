<?php

namespace backend\modules\log\actions;

class LogAction extends HistoryAction
{
    /**
     * @var string
     */
    public $view = '@backend/modules/log/views/log.php';

    /**
     * @param int $id
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        return $this->controller->render($this->view, [
            'model' => $this->findModel($id),
            'parent' => $this->parent,
        ]);
    }
}
