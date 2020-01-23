<?php

namespace frontend\controllers;

use common\models\City;
use common\models\Country;
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
    public function actionCountry($search = '')
    {
        $query = Country::find()->asArray();

        if ($search) {
            $query->filterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $country) {
            $results[] = [
                'id' => $country['id_country'],
                'text' => $country['name'],
            ];
        }

        return ['results' => $results];
    }

    /**
     * @param int $id_country
     * @param string $search
     * @return array
     */
    public function actionRegion($id_country = null, $search = '')
    {
        $region_table = Region::tableName();

        $query = Region::find()
            ->joinWith('houses', false);

        if (!empty($id_country))
        {
            $query->filterWhere([House::tableName() . '.id_country' => (int)$id_country]);
        }

        $query->groupBy($region_table . '.id_region')
            ->orderBy([$region_table . '.name' => SORT_ASC])
            ->asArray();

        if ($search)
            $query->filterWhere(['ilike', $region_table.'.name', $search]);

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
    public function actionSubregion($id_region = null, $search = '')
    {
        $query = Subregion::find()
            ->joinWith('houses', false);

        if (!empty($id_region)) {
            $query->filterWhere([House::tableName() . '.id_region' => (int)$id_region]);
        }

        $query->groupBy(Subregion::tableName() . '.id_subregion')
            ->orderBy([Subregion::tableName() . '.name' => SORT_ASC])
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', Subregion::tableName() . '.name', $search]);
        }

        $results = [];
        foreach ($query->all() as $subregion) {
            $results[] = [
                'id' => $subregion['id_subregion'],
                'text' => $subregion['name'],
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
        if (!$id_region && !$id_subregion)
        {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => 'id_region или id_subregion',
            ]));
        }

        $id_region = (int)$id_region;
        $id_subregion = (int)$id_subregion;

        $query = City::find()
            ->joinWith('houses', false)
            ->filterWhere([
                House::tableName() . '.id_region' => $id_region?$id_region:null,
                House::tableName() . '.id_subregion' => $id_subregion?$id_subregion:null,
            ])
            ->groupBy(City::tableName() . '.id_city')
            ->orderBy([City::tableName() . '.name' => SORT_ASC])
            ->asArray();

        if ($search)
            $query->andFilterWhere(['ilike', City::tableName() . '.name', $search]);

        $results = [];
        foreach ($query->all() as $city) {
            $results[] = [
                'id' => $city['id_city'],
                'text' => $city['name'],
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
        $id_city = (int)$id_city;

        $query = District::find()
            ->joinWith('houses', false)
            ->filterWhere([House::tableName() . '.id_city' => $id_city?$id_city:null])
            ->groupBy(District::tableName() . '.id_district')
            ->orderBy([District::tableName() . '.name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', District::tableName() . '.name', $search]);
        }

        $results = [];
        foreach ($query->all() as $district) {
            $results[] = [
                'id' => $district['id_district'],
                'text' => $district['name'],
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
        $id_city = (int)$id_city;

        $query = Street::find()
            ->joinWith('houses', false)
            ->filterWhere([House::tableName() . '.id_city' => $id_city?$id_city:null])
            ->groupBy(Street::tableName() . '.id_street')
            ->orderBy([Street::tableName() . '.name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', Street::tableName() . '.name', $search]);
        }

        $results = [];
        foreach ($query->all() as $street) {
            $results[] = [
                'id' => $street['id_street'],
                'text' => $street['name'],
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
        $id_street = (int)$id_street;
        if (empty($id_street)) {
            return ['results' => []];
        };

        $query = House::find()
            ->filterWhere(['id_street' => $id_street?$id_street:null])
            ->groupBy('id_house')
            ->orderBy(['name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $house) {
            $results[] = [
                'id' => $house['id_house'],
                'text' => $house['name'],
                'postalcode' => $house['postalcode'],
                'lat' => $house['lat'],
                'lon' => $house['lon'],
            ];
        }

        if (empty($results)) {
            $results = [
                'id' => null,
                'text' => $search,
                'postalcode' => '',
                'lat' => '',
                'lon' => '',
            ];
        }

        return ['results' => $results];
    }
}
