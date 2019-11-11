<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

use common\models\Pdocument;
use frontend\models\WorkflowForm;

class WorkflowController extends \yii\web\Controller
{

    public function actionIn()
    {
        $model = new WorkflowForm();

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstances($model, 'file');

//            if ($model->file && $model->validate()) {
              if ($model->file) {
                foreach ($model->file as $file) {
                    $filename = 'assets/uploads/' . $file->baseName . '.' . $file->extension;

                    if(file_exists($filename))
                        unlink($filename);

                    $file->saveAs('assets/uploads/' . $file->baseName . '.' . $file->extension);

                    $content = file_get_contents($filename);

                    //echo $file->type;
                    //die();

                    switch($file->type){
                        case 'text/xml':
                            $doc = new Pdocument;
                            $doc->parseAndSave($content);
                    }


                }
            }
        }
    }

    public function actionTestupload()
    {
        $model = new WorkflowForm();
        return $this->render('testupload', ['model' => $model]);
    }

}
