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

use common\models\Emgis;

class EstateController extends \yii\web\Controller
{

    public function actionIndex($page = null)
    {
        //echo("ESTATE"); 
        
        $emconnect = new Emgis;

        $cat = $emconnect->CategoryClassificator();
        $allowed = $emconnect->AllowedClassificator();
        $cat2 = $emconnect->EncumbranceClassificator();
        $cat3 = $emconnect->RightClassificator();


        $data = $emconnect->Remedy65Request();
        /*
        echo "<pre>";
        var_dump($cat);
        var_dump($cat2);
        var_dump($cat3);
        echo "</pre>";
        die();
        */
        /*
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
        */
        $result = false;

        return $this->render('check', [
            'AreaCategories' => $cat,
            'Allowed' => $allowed, 
            'page' => $page, 
            'result' => $result ]);
    }

    public function actionTest()
    {
        $emconnect = new Emgis;

        $rows = $emconnect->Remedy1Request();
        //$rows = $emconnect->AllowedClassificator();
        echo "<pre>";
        var_dump($rows);
        echo "</pre>";

        die();

        $cat = $emconnect->CategoryClassificator();
        $cat2 = $emconnect->EncumbranceClassificator();
        $cat3 = $emconnect->RightClassificator();

        echo "<pre>";
        var_dump($cat);
        var_dump($cat2);
        var_dump($cat3);
        echo "</pre>";
        die();

    }

}