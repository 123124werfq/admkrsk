<?php

namespace backend\modules\log\actions;

use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;

class RestoreAction extends HistoryAction
{
    /**
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function run($id)
    {
        $model = $this->findModel($id);

        /* @var ActiveRecord $parentModel */
        $parentModel = !empty($parent) ? $model->entity->{$parent['relation']} : null;

        if ($model->restore()) {
            Yii::$app->session->setFlash('success', 'Изменения восстановлены');

            return $this->controller->redirect(['view', 'id' => $parentModel ? $parentModel->primaryKey : $model->entity->primaryKey]);
        }

        Yii::$app->session->setFlash('danger', 'При восстановлении изменений произошла ошибка');

        return $this->controller->refresh();
    }
}
