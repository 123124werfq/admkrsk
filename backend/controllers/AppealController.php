<?php

namespace backend\controllers;

use common\models\ServiceTarget;
use Yii;
use common\models\AppealRequest;
use backend\models\search\AppealSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\CollectionColumn;

/**
 * ServiceAppealController implements the CRUD actions for ServiceAppeal model.
 */
class AppealController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ServiceAppeal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppealSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServiceAppeal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $sa = $this->findModel($id);

        if (empty($sa->collectionRecord))
            throw new NotFoundHttpException('Ошибка чтения данных');

        $insertedData = $sa->collectionRecord->getData();

        $columns = CollectionColumn::find()->where(['id_collection' => $sa->collectionRecord->id_collection])->indexBy('id_column')->orderBy('ord')->all();

        foreach ($insertedData as $rkey => $ritem)
        {
            if($columns[$rkey]->alias == 'id_target') {
                $target = ServiceTarget::findOne($ritem);
                $formFields[$columns[$rkey]->alias] = ['value' => $target->reestr_number." ".$target->name, 'name' => $columns[$rkey]->name, 'ord' => $columns[$rkey]->ord];
            }
            else
                $formFields[$columns[$rkey]->alias] = ['value' => empty($ritem)?"[не заполнено]":$ritem, 'name' => $columns[$rkey]->name, 'ord' => $columns[$rkey]->ord];
        }

        usort($formFields, function($a, $b){return ($a['ord']<$b["ord"])?-1:1;});

        $attachments = $sa->collectionRecord->getAllMedias();

        return $this->render('view', [
            'model' => $sa,
            'formFields' => $formFields,
            'attachments' => $attachments
        ]);
    }

    /**
     * Creates a new ServiceAppeal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AppealRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_appeal]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ServiceAppeal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id_appeal]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDoc($id)
    {
        $appeal = $this->findModel($id);

        //$data = $appeal->collectionRecord->getData(true);

        //$form = $appeal->collectionRecord->collection->form;

        $export_path = $appeal->collectionRecord->collection->form->makeDoc($appeal->collectionRecord);

        /*
        $media = $appeal->target->service->template;
        $url = $media->getUrl();

        $template = file_get_contents($url);
        $root = Yii::getAlias('@app');
        $template_path = $root.'/runtime/template_'.$media->id_media.'_'.time().'docx';
        $template = file_put_contents($template_path);

        $export_path = \common\components\worddoc\WordDoc::makeDocByForm($form, $data, $template_path);
        */

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$appeal->target->reestr_number.' '.$appeal->created_at.'.docx"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($export_path));
        readfile($export_path);

        unlink($export_path);
    }

    /**
     * Deletes an existing ServiceAppeal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ServiceAppeal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServiceAppeal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppealRequest::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
