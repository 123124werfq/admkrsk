<?php

namespace frontend\controllers;
use common\models\Address;
use common\models\SearchSitemap;
use yii\data\SqlDataProvider;
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
        $order = Yii::$app->request->get('ord', false);

        $result = [];
        if(!empty($query)) {
            $sqlQuery = SearchSitemap::fulltext($query, $order == 'date', true);

            $sqlCountQuery = str_replace("SELECT *", "SELECT COUNT(*)", $sqlQuery);

            $count = Yii::$app->db->createCommand($sqlCountQuery)->queryScalar();

            $provider = new SqlDataProvider([
                'sql' => $sqlQuery,
                'totalCount' => $count,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => [
                        'title',
                        'view_count',
                        'created_at',
                    ],
                ],
            ]);

        }

        return $this->render('index', ['provider' => $provider, 'request' => htmlspecialchars($query)]);
    }

}
