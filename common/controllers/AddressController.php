<?php

namespace common\controllers;

use common\models\City;
use common\models\Country;
use common\models\District;
use common\models\House;
use common\models\Region;
use common\models\Street;
use common\models\Place;
use common\models\Subregion;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class AddressController extends \yii\web\Controller
{
    /**
     * @param string $search
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCountry($search = '')
    {
        $query = Country::find()
            ->select(['id_country', 'name'])
            ->asArray();

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

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_country
     * @param string $search
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRegion($id_country = null, $search = '')
    {
        $id_country = (int) $id_country;

        $query = Region::find()
            ->select(['id_region', 'name'])
            ->filterWhere(['id_country' => $id_country ?: null])
            ->groupBy('id_region')
            ->orderBy(['name' => SORT_ASC])
            ->asArray();

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

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_region
     * @param string $search
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSubregion($id_region = null, $search = '')
    {
        $id_region = (int) $id_region;

        $query = Subregion::find()
            ->select(['id_subregion', 'name'])
            ->filterWhere(['id_region' => $id_region ?: null])
            ->groupBy('id_subregion')
            ->orderBy(['name' => SORT_ASC])
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $subregion) {
            $results[] = [
                'id' => $subregion['id_subregion'],
                'text' => $subregion['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_region
     * @param int $id_subregion
     * @param string $search
     * @return Response
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCity($id_region = null, $id_subregion = null, $search = '')
    {
        if (!$id_region && !$id_subregion) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => 'id_region или id_subregion',
            ]));
        }

        $id_region = (int) $id_region;
        $id_subregion = (int) $id_subregion;

        $query = City::find()
            ->select(['id_city', 'name'])
            ->filterWhere([
                'id_region' => $id_region ?: null,
                'id_subregion' => $id_subregion ?: null,
            ])
            ->groupBy('id_city')
            ->orderBy(['name' => SORT_ASC])
            ->limit(100)
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $city) {
            $results[] = [
                'id' => $city['id_city'],
                'text' => $city['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_city
     * @param string $search
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDistrict($id_city, $search = '')
    {
        $id_city = (int) $id_city;

        $query = District::find()
            ->select(['id_district', 'name'])
            ->filterWhere(['id_city' => $id_city ?: null])
            ->groupBy('id_district')
            ->orderBy(['name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($search) {
            $query->andFilterWhere(['ilike', 'name', $search]);
        }

        $results = [];
        foreach ($query->all() as $district) {
            $results[] = [
                'id' => $district['id_district'],
                'text' => $district['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_city
     * @param int $id_district
     * @param string $search
     * @return Response
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionStreet($id_city = null, $id_district = null, $search = '')
    {
        if (!$id_city && !$id_district) {
            throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => 'id_city или id_district',
            ]));
        }

        $id_city = (int) $id_city;
        $id_district = (int) $id_district;

         $query = Street::find()
            ->select([Street::tableName() . '.id_street', Street::tableName() . '.name'])
            ->joinWith('districts', false)
            ->filterWhere([
                Street::tableName() . '.id_city' => $id_city,
                District::tableName() . '.id_district' => $id_district ?: null,
            ])
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

        return $this->asJson(['results' => $results]);
    }

    /**
     * @param int $id_street
     * @param string $search
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionHouse($id_street, $is_active = 1, $search = '')
    {
        $id_street = (int) $id_street;

        if (empty($id_street)) {
            return $this->asJson(['results' => []]);
        };

        $query = House::find()
            ->select(['id_house', 'map_house.name', 'postalcode', 'lat', 'lon','district.name as districtname','map_house.id_district'])
            ->joinWith('district as district')
            ->filterWhere(['id_street' => $id_street ?: null])
            ->groupBy('id_house, district.name')
            ->orderBy(['map_house.name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($is_active) {
            $query->andFilterWhere(['map_house.is_active' => $is_active]);
        }

        if ($search)
            $query->andFilterWhere(['ilike', 'map_house.name', $search]);

        $results = [];
        foreach ($query->all() as $house) {
            $results[] = [
                'id' => $house['id_house'],
                'text' => $house['name'],
                'district' => $house['districtname'],
                'id_district' => $house['id_district'],
                'postalcode' => $house['postalcode'],
                'lat' => $house['lat'],
                'lon' => $house['lon'],
            ];
        }

        /*if (empty($results)) {
            $results[] = [
                    //'id' => null,
                    'text' => $search,
                    'postalcode' => '',
                    'district'=> '',
                    'results'=>'',
                ];
        }*/

        return $this->asJson(['results' => $results]);
    }

    public function actionPlace($id_house = null, $search = '')
    {
        $id_house = (int) $id_house;

        $query = Place::find()
            ->select([Place::tableName() . '.id_place', Place::tableName() . '.name'])
            ->filterWhere([
                Place::tableName() . '.id_house' => $id_house ?: null,
            ])
            ->orderBy([Place::tableName() . '.name' => SORT_ASC])
            ->limit(20)
            ->asArray();

        if ($search)
            $query->andFilterWhere(['ilike', Place::tableName() . '.name', $search]);

        $results = [];

        foreach ($query->all() as $data)
        {
            $results[] = [
                'id' => $data['id_place'],
                'text' => $data['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    public function actionHouseByPlace()
    {
        $id = (int)Yii::$app->request->post('id');

        if (empty($id))
            return  $this->asJson([]);

        $place = Place::findOne($id);

        if (empty($place))
            return  $this->asJson([]);

        $output = $place->house->getArrayData($place);

        return $this->asJson(
            $output
        );
    }
}
