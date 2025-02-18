<?php

namespace frontend\controllers;

use common\models\AppealRequest;
use common\models\AppealState;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
//use frontend\modules\api\models\CollectionRecord;
use Yii;
use common\models\Page;
use common\models\Collection;
use common\models\Form;
use common\models\HrProfile;
use yii\web\BadRequestHttpException;

use common\models\ServiceAppeal;
use common\models\ServiceAppealState;

class CheckController extends \yii\web\Controller
{

    public function actionIndex($page = null)
    {
        $request = Yii::$app->request->get('query', null);


        $appeals = ServiceAppeal::find()->where(['number_internal' => $request])->all();

        if($appeals)
            return $this->render('//service/userhistory', [
                'page' => $page,
                'appeals' => $appeals
            ]);


        $result = false;

        if(!empty($request))
            $result = 'В реестре не содержится информация по запрошенному обращению.';

        return $this->render('check', ['page' => $page, 'result' => $result ]);
    }

}