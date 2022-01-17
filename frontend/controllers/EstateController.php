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
        $emconnect = new Emgis;

        $cat = $emconnect->CategoryClassificator();
        $allowed = $emconnect->AllowedClassificator();
        $encumbrances = $emconnect->EncumbranceClassificator();
        $rights = $emconnect->RightClassificator();

        $result = false;
        $count = -1;

        if(isset($_REQUEST['infotype']))
        {
            switch ((int)$_REQUEST['infotype']) {
                case 1:
                    $filter = [];
                    
                    if(isset($_REQUEST['area_category']) && $_REQUEST['area_category'] != "не указано" )
                        $filter[] = "FunCls1_ClsName eq ".$_REQUEST['area_category'];
                    if(isset($_REQUEST['allowed_use']) && !empty(trim($_REQUEST['allowed_use'])))
                        $filter[] = "contains(Terr_AllowType,".trim($_REQUEST['allowed_use']).")";                    

                    $filter = implode(" and ", $filter);

                    $count = $emconnect->Remedy65Request(['count' => 1, "filter" => ($filter)]);
                    $result = $emconnect->Remedy65Request(["filter" => ($filter)]);                    
                    break;
                
                default:
                    # code...
                    break;
            }

        }

//var_dump($result['fileds']); die();
        //$data = $emconnect->Remedy65Request();
        /*
        echo "<pre>";
        var_dump($allowed);
        //var_dump($cat2);
        //var_dump($cat3);
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

        return $this->render('check', [
            'areaCategories' => $cat ?? [],
            'allowed' => $allowed ?? [], 
            'rights' => $rights ?? [],
            'encumbrances' => $encumbrances ?? [], 
            'page' => $page, 
            'result' => $result,
            'count' => $count
         ]);
    }

    public function actionTest()
    {
        $emconnect = new Emgis;

        //$rows = $emconnect->Remedy65Request(['count' => 1]);
        $rows = $emconnect->AllowedClassificator();
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