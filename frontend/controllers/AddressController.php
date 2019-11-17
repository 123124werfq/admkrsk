<?php

namespace frontend\controllers;

use common\models\City;
use common\models\District;
use common\models\House;
use common\models\Region;
use common\models\Street;
use common\models\Subregion;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class AddressController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    /**
     * @param string $search
     * @return array
     */
    public function actionRegion($search = '')
    {
        $query = Region::find()->asArray();

        if ($search) {
            $query->filterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $region) {
            $results[] = [
                'id' => $region['id_region'],
                'text' => $region['name'],
            ];
        }

        return ['results' => $results];
    }

    /**
     * @param int $id_region
     * @param string $search
     * @return array
     */
    public function actionSubregion($id_region, $search = '')
    {
        $query = Subregion::find()
            ->select([Subregion::tableName().'.id_subregion',Subregion::tableName().'.name'])
            ->joinWith('houses', false)
            ->filterWhere([House::tableName() . '.id_region' => $id_region])
            ->groupBy(Subregion::tableName() . '.id_subregion, '.Subregion::tableName().'.name')
            ->orderBy([Subregion::tableName() . '.name' => SORT_ASC])
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', Subregion::tableName() . '.name', $search]);
        }

        $results = [];
        foreach ($query->all() as $subregion) {
            $results[] = [
                'id_subregion' => $subregion['id_subregion'],
                'name' => $subregion['name'],
            ];
        }

        return ['results' => $results];
    }

    /**
     * @param int $id_region
     * @param int $id_subregion
     * @param string $search
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCity($id_region = null, $id_subregion = null, $search = '')
    {
        if (!$id_region && !$id_subregion) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => 'id_region или id_subregion',
            ]));
        }

        $query = City::find()
            ->joinWith('houses', false)
            ->filterWhere([
                House::tableName() . '.id_region' => $id_region,
                House::tableName() . '.id_subregion' => $id_subregion,
            ])
            ->groupBy(City::tableName() . '.id_city')
            ->orderBy([City::tableName() . '.name' => SORT_ASC])
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', City::tableName() . '.name', $search]);
        }

        $results = [];
        foreach ($query->all() as $city) {
            $results[] = [
                'id_city' => $city['id_city'],
                'name' => $city['name'],
            ];
        }

        return ['results' => $results];
    }

    /**
     * @param int $id_city
     * @param string $search
     * @return array
     */
    public function actionDistrict($id_city, $search = '')
    {
        $query = District::find()
            ->select([District::tableName().'.id_district',District::tableName().'.name'])
            ->joinWith('houses', false)
            ->filterWhere([House::tableName() . '.id_city' => $id_city])
            ->groupBy(District::tableName() . '.id_district,'.District::tableName() . '.name')
            ->orderBy([District::tableName() . '.name' => SORT_ASC])
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', District::tableName() . '.name', $search]);
        }

        $results = [];
        foreach ($query->all() as $district) {
            $results[] = [
                'id_district' => $district['id_district'],
                'name' => $district['name'],
            ];
        }

        return ['results' => $results];
    }

    /**
     * @param int $id_city
     * @param string $search
     * @return array
     */
    public function actionStreet($id_city, $search = '')
    {
        $query = Street::find()
            ->joinWith('houses', false)
            ->filterWhere([House::tableName() . '.id_city' => $id_city])
            ->groupBy(Street::tableName() . '.id_street')
            ->orderBy([Street::tableName() . '.name' => SORT_ASC])
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', Street::tableName() . '.name', $search]);
        }

        $results = [];
        foreach ($query->all() as $street) {
            $results[] = [
                'id_street' => $street['id_street'],
                'name' => $street['name'],
            ];
        }

        return ['results' => $results];
    }

    /**
     * @param int $id_street
     * @param string $search
     * @return array
     */
    public function actionHouse($id_street, $search = '')
    {
        $query = House::find()
            ->filterWhere(['id_street' => $id_street])
            ->groupBy('id_house')
            ->orderBy(['name' => SORT_ASC])
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $house) {
            $results[] = [
                'id_house' => $house['id_house'],
                'name' => $house['name'],
            ];
        }

        return ['results' => $results];
    }
}
