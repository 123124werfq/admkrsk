<?php

namespace console\controllers;

use common\helpers\ProgressHelper;
use common\models\City;
use common\models\District;
use common\models\FiasHouse;
use common\models\House;
use common\models\Region;
use common\models\Street;
use common\models\Subregion;
use Yii;
use yii\console\Controller;

class FiasController extends Controller
{
    /**
     * Обновление адресов
     * @throws \Exception
     */
    public function actionUpdateAddresses()
    {
        //$transaction = Yii::$app->db->beginTransaction();
        try {
            $query = FiasHouse::find()->andWhere(['divtype' => 0]);

            $count = 0;
            $fiasHouseCount = $query->count();

            Yii::$app->db->createCommand('TRUNCATE TABLE ' . Region::tableName() . ' CASCADE')->execute();
            Yii::$app->db->createCommand()->resetSequence(Region::tableName())->execute();
            Yii::$app->db->createCommand('TRUNCATE TABLE ' . Subregion::tableName() . ' CASCADE')->execute();
            Yii::$app->db->createCommand()->resetSequence(Subregion::tableName())->execute();
            Yii::$app->db->createCommand('TRUNCATE TABLE ' . City::tableName() . ' CASCADE')->execute();
            Yii::$app->db->createCommand()->resetSequence(City::tableName())->execute();
            Yii::$app->db->createCommand('TRUNCATE TABLE ' . District::tableName() . ' CASCADE')->execute();
            Yii::$app->db->createCommand()->resetSequence(District::tableName())->execute();
            Yii::$app->db->createCommand('TRUNCATE TABLE ' . Street::tableName() . ' CASCADE')->execute();
            Yii::$app->db->createCommand()->resetSequence(Street::tableName())->execute();
            Yii::$app->db->createCommand('TRUNCATE TABLE ' . House::tableName() . ' CASCADE')->execute();
            Yii::$app->db->createCommand()->resetSequence(House::tableName())->execute();

            ProgressHelper::startProgress($count, $fiasHouseCount, "Обновление адресов: ");

            /* @var FiasHouse $fiasHouse */
            foreach ($query->each() as $fiasHouse) {
                $region = $subregion = $city = $district = $street = null;

                if (($street = Street::findOne(['aoguid' => $fiasHouse->aoguid])) === null) {
                    $street = new Street([
                        'aoguid' => $fiasHouse->aoguid,
                        'name' => $fiasHouse->addrObj->addressName,
                    ]);
                    $street->save();
                }

                if (($districtName = District::getDistrictNameByOKATO($fiasHouse->okato)) !== null) {
                    if (($district = District::findOne(['name' => $districtName])) === null) {
                        $district = new District(['name' => $districtName]);
                        $district->save();
                    }
                }

                foreach ($fiasHouse->addrObj->parents as $parent) {
                    if ($parent->aolevel == 1) {
                        if (($region = Region::findOne(['aoguid' => $parent->aoguid])) === null) {
                            $region = new Region([
                                'aoguid' => $parent->aoguid,
                                'name' => $parent->addressName,
                            ]);
                            $region->save();
                        }
                    } elseif ($parent->aolevel == 3) {
                        if (($subregion = Subregion::findOne(['aoguid' => $parent->aoguid])) === null) {
                            $subregion = new Subregion([
                                'aoguid' => $parent->aoguid,
                                'name' => $parent->addressName,
                            ]);
                            $subregion->save();
                        }
                    } else {
                        if (($city = City::findOne(['aoguid' => $parent->aoguid])) === null) {
                            $city = new City([
                                'aoguid' => $parent->aoguid,
                                'name' => $parent->addressName,
                            ]);
                            $city->save();
                        }
                    }
                }

                /** @var Region $region */
                /** @var Subregion $subregion */
                /** @var City $city */
                $address = new House([
                    'id_region' => $region ? $region->id_region : null,
                    'id_subregion' => $subregion ? $subregion->id_subregion : null,
                    'id_city' => $city ? $city->id_city : null,
                    'id_district' => $district ? $district->id_district : null,
                    'id_street' => $street ? $street->id_street : null,
                    'houseguid' => $fiasHouse->houseguid,
                    'postalcode' => $fiasHouse->postalcode,
                    'name' => $fiasHouse->houseName,
                    'fullname' => $fiasHouse->fullName,
                ]);

                if ($address->save()) {
                    $count++;
                }

                ProgressHelper::updateProgress($count, $fiasHouseCount);
            }
            ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);

            //$transaction->commit();

            $this->stdout(Yii::t('app', 'Обновлено {count} адресов', ['count' => $count]) . PHP_EOL);
        } catch (\Exception $e) {
            //$transaction->rollBack();
            throw $e;
        }
    }

    public function actionTest()
    {
        $house = FiasHouse::findOne(['houseguid' => 'b9eb6e82-6f6c-42d7-9163-83e9562ea757']);

        print_r($house->fullName);
        echo PHP_EOL;
    }
}