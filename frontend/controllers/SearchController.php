<?php

namespace frontend\controllers;
use common\models\Address;
use common\models\SearchSitemap;
use Yii;

class SearchController extends \yii\web\Controller
{
    public function actionAddress()
    {
        $output = [];

        if (isset($_GET['term']))
        {
            $addresses = Address::find()->where(['like', 'address', str_replace([' ',',','.'], '%', '%'.$_GET['term'].'%'),false])->limit(10)->all();

            foreach ($addresses as $key=>$data)
                $output[] = array('id'=>$key, 'label'=>$data->address, 'value'=>$data->address);
        }

        return json_encode($output);
    }

    public function actionIndex()
    {
        $query = Yii::$app->request->get('q');

        $result = [];
        if(!empty($query))
            $result = SearchSitemap::fulltext($query);

        return $this->render('index', ['result' => $result, 'request' => htmlspecialchars($query)]);
    }

}
