<?php

namespace console\controllers;

use common\helpers\ProgressHelper;
use common\models\City;
use common\models\Country;
use common\models\District;
use common\models\FiasAddrObj;
use common\models\FiasHouse;
use common\models\FiasUpdateHistory;
use common\models\House;
use common\models\Region;
use common\models\Street;
use common\models\Subregion;
use GuzzleHttp\Client;
use SoapClient;
use SoapFault;
use Yii;
use yii\base\ErrorException;
use yii\base\InvalidValueException;
use yii\console\Controller;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class FiasController extends Controller
{
    public $region;
    public $limit = 1000;

    private $lastVersion = null;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID),
            $actionID == 'update' ? ['region'] : [],
            $actionID == 'update-location' ? ['limit'] : []
        );
    }

    /**
     * Обновление адресов
     * @throws \Exception
     */
    public function actionUpdateAddresses()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $regionQuery = Region::findWithDeleted()
                ->where(['id_country' => null]);

            $count = 0;
            $regionCount = $regionQuery->count();
            ProgressHelper::startProgress($count, $regionCount, "Обновление регионов: ");
            /* @var Region $region */
            foreach ($regionQuery->each() as $region) {
                $id_country = House::findWithDeleted()
                    ->select('id_country')
                    ->groupBy('id_country')
                    ->scalar();

                if ($id_country) {
                    $region->updateAttributes(['id_country' => $id_country]);
                }

                $count++;

                ProgressHelper::updateProgress($count, $regionCount);
            }
            ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);



            $subregionQuery = Subregion::findWithDeleted()
                ->where(['id_region' => null]);

            $count = 0;
            $subregionCount = $subregionQuery->count();
            ProgressHelper::startProgress($count, $subregionCount, "Обновление районов: ");
            /* @var Subregion $subregion */
            foreach ($subregionQuery->each() as $subregion) {
                $id_region = House::findWithDeleted()
                    ->select('id_region')
                    ->where(['id_subregion' => $subregion->id_subregion])
                    ->groupBy('id_region')
                    ->scalar();

                if ($id_region) {
                    $subregion->updateAttributes(['id_region' => $id_region]);
                }

                $count++;

                ProgressHelper::updateProgress($count, $subregionCount);
            }
            ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);



            $cityQuery = City::findWithDeleted()
                ->where([
                    'or',
                    ['id_region' => null],
                    ['id_subregion' => null],
                ]);

            $count = 0;
            $cityCount = $cityQuery->count();
            ProgressHelper::startProgress($count, $cityCount, "Обновление городов: ");
            /* @var City $city */
            foreach ($cityQuery->each() as $city) {
                $id_region = House::findWithDeleted()
                    ->select('id_region')
                    ->where(['id_city' => $city->id_city])
                    ->groupBy('id_region')
                    ->scalar();

                $id_subregion = House::findWithDeleted()
                    ->select('id_subregion')
                    ->where(['id_city' => $city->id_city])
                    ->groupBy('id_subregion')
                    ->scalar();

                if ($id_region || $id_subregion) {
                    $city->updateAttributes(['id_region' => $id_region, 'id_subregion' => $id_subregion]);
                }

                $count++;

                ProgressHelper::updateProgress($count, $cityCount);
            }
            ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);



            $districtQuery = District::findWithDeleted()
                ->where(['id_city' => null]);

            $count = 0;
            $districtCount = $districtQuery->count();
            ProgressHelper::startProgress($count, $districtCount, "Обновление районов городов: ");
            /* @var District $district */
            foreach ($districtQuery->each() as $district) {
                $id_city = City::findWithDeleted()
                    ->select('id_city')
                    ->where(['name' => 'г Красноярск'])
                    ->scalar();

                if ($id_city) {
                    $district->updateAttributes(['id_city' => $id_city]);
                }

                $count++;

                ProgressHelper::updateProgress($count, $districtCount);
            }
            ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);



            $streetQuery = Street::findWithDeleted()
                ->joinWith('districts', false)
                ->where([
                    'or',
                    [Street::tableName() . '.id_city' => null],
                    [District::tableName() . '.id_district' => null],
                ]);

            $count = 0;
            $streetCount = $streetQuery->count();
            $districts = District::findWithDeleted()->indexBy('id_district')->all();
            ProgressHelper::startProgress($count, $streetCount, "Обновление улиц: ");
            /* @var Street $street */
            foreach ($streetQuery->each() as $street) {
                $id_city = House::findWithDeleted()
                    ->select('id_city')
                    ->where(['id_street' => $street->id_street])
                    ->groupBy('id_city')
                    ->scalar();

                $districtIds = House::findWithDeleted()
                    ->select('id_district')
                    ->where([
                        'and',
                        ['id_street' => $street->id_street],
                        ['not', ['id_district' => null]],
                    ])
                    ->groupBy('id_district')
                    ->column();

                if ($id_city) {
                    $street->updateAttributes(['id_city' => $id_city]);
                }

                foreach ($districtIds as $id_district) {
                    if (isset($districts[$id_district])) {
                        $street->link('districts', $districts[$id_district]);
                    }
                }

                $count++;

                ProgressHelper::updateProgress($count, $streetCount);
            }
            ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionUpdateFullname()
    {
        foreach (House::find()->each() as $house) {
            /* @var House $house */
            $house->updateAttributes(['fullname' => $house->getFullName()]);
        }
    }

    public function actionUpdateLocation()
    {
        $houseQuery = House::find()
            ->where(['sputnik_updated_at' => null])
            ->limit($this->limit);

        foreach ($houseQuery->each() as $house) {
            /* @var House $house */
            $house->updateLocation();
        }
    }

    public function actionUpdate()
    {
        $this->lastVersion = FiasUpdateHistory::find()->max('version');

        $client = new SoapClient('https://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL');

        try {
            $response = $client->GetAllDownloadFileInfo();
        } catch (SoapFault $exception) {
            $updateHistory = new FiasUpdateHistory(['text' => $exception->getMessage()]);
            $updateHistory->save();
            exit(0);
        }

        $updates = ArrayHelper::map($response->GetAllDownloadFileInfoResult->DownloadFileInfo, 'VersionId', function (\StdClass $object) {
            return [
                'version' => $object->VersionId,
                'text' => $object->TextVersion,
                'file' => $this->lastVersion ? $object->FiasDeltaDbfUrl : $object->FiasCompleteDbfUrl,
            ];
        });
        ArrayHelper::multisort($updates, 'version');

        if ($this->lastVersion) {
            foreach ($updates as $key => $update) {
                if ($update['version'] <= $this->lastVersion) {
                    unset($updates[$key]);
                }
            }
        } else {
            $updates = [array_pop($updates)];
        }

        foreach ($updates as $update) {
            $this->fiasUpdate($update);
        }
    }

    /**
     * @param $update
     * @throws ErrorException
     * @throws \yii\base\Exception
     */
    private function fiasUpdate($update)
    {
        $updateHistory = new FiasUpdateHistory($update);

        FileHelper::createDirectory(Yii::getAlias('@runtime/fias_update'));

        $file = $this->downloadFile($updateHistory);

        $path = $this->extractFile($file);

        $this->updateData($path);

        $updateHistory->save();
    }

    private function updateData($path)
    {
        foreach (FileHelper::findFiles($path, ['only' => ['ADDROB' . $this->region . '*']]) as $file) {
            $this->importDbf($file);
        }

        foreach (FileHelper::findFiles($path, ['only' => ['HOUSE' . $this->region . '*']]) as $file) {
            $this->importDbf($file);
        }
    }

    /**
     * @param FiasUpdateHistory $updateHistory
     * @return string
     */
    private function downloadFile($updateHistory)
    {
        $filename = Yii::getAlias('@runtime/fias_update/' . $updateHistory->version);
        if ($this->lastVersion) {
            $filename .= '_fias_delta_dbf.rar';
        } else {
            $filename .= '_fias_dbf.rar';
        }

        $client = new Client();
        $client->get($updateHistory->file, ['save_to' => $filename]);

        return $filename;
    }

//    public function actionTest()
//    {
//        $file = Yii::getAlias('@runtime/fias_update/603_fias_delta_dbf.rar');
//
//        $path = $this->extractFile($file);
//
//        foreach (FileHelper::findFiles($path, ['only' => ['ADDROB' . $this->region . '*']]) as $file) {
//            $this->importDbf($file);
//        }
//
//        foreach (FileHelper::findFiles($path, ['only' => ['HOUSE' . $this->region . '*']]) as $file) {
//            $this->importDbf($file);
//        }
//    }

    /**
     * @param string $archive
     * @return string
     * @throws ErrorException
     */
    private function extractFile($archive)
    {
        $path = Yii::getAlias('@runtime/fias_update/' . basename($archive, '.rar') . DIRECTORY_SEPARATOR);

        if (is_dir($path)) {
            FileHelper::removeDirectory($path);
        }

        exec("unrar x $archive $path");

        return $path;
    }

    /**
     * @param $filename
     * @return int
     * @throws Exception
     */
    private function importDbf($filename)
    {
        if (!$db = @dbase_open($filename, 0)) {
            $this->stderr("Не удалось открыть DBF файл: '$filename'\n");
            return 1;
        }

        $classMap = [
            '/^.*ADDROB\d\d\.DBF$/' => FiasAddrObj::class,
            '/^.*HOUSE\d\d\.DBF$/' => FiasHouse::class,
        ];

        $modelClass = false;
        foreach ($classMap as $pattern => $className) {
            if (preg_match($pattern, $filename)) {
                $modelClass = $className;
                break;
            }
        }

        if ($modelClass === false) {
            $this->stderr("Не поддерживаемый DBF файл: '$filename'\n");
            return 1;
        }

        $rowsCount = dbase_numrecords($db);
        $this->stdout("Записей в DBF файле '$filename': $rowsCount\n");

        $transaction = Yii::$app->db->beginTransaction();

        $houseGuids = [];
        $count = $insert = $update = $j = 0;

        ProgressHelper::startProgress($count, $rowsCount, "Обработка записей: ");

        for ($i = 1; $i <= $rowsCount; $i++) {
            $row = dbase_get_record_with_names($db, $i);

            switch ($modelClass) {
                case FiasAddrObj::class:
                    $condition = ['aoguid' => $row['AOGUID']];
                    break;
                case FiasHouse::class:
                    $condition = ['houseguid' => $row['HOUSEGUID']];
                    $houseGuids[] = $row['HOUSEGUID'];
                    break;
                default:
                    break;
            }

            /* @var ActiveRecord $model */
            if (!$model = $modelClass::findOne($condition)) {
                $model = new $modelClass;
            }

            foreach ($row as $key => $value) {
                if ($key == 'deleted') {
                    continue;
                }

                $key = strtolower($key);
                $model->{$key} = trim(mb_convert_encoding($value, 'UTF-8', 'CP866'));
            }

            $isNewRecord = $model->isNewRecord;

            if (!$model->save()) {
                echo get_class($model) . PHP_EOL;
                print_r($model->errors);
                print_r($model->attributes);
                print_r($row);
            } else {
                if ($isNewRecord) {
                    $insert++;
                } else {
                    $update++;
                }
            }

            $j++;
            $count++;

            if ($j == 1000) {
                if ($houseGuids) {
                    $this->updateAddresses(['houseguid' => $houseGuids], false);
                }
                $houseGuids = [];
                $transaction->commit();
                $j = 0;
                ProgressHelper::updateProgress($count, $rowsCount);
                $transaction = Yii::$app->db->beginTransaction();
            }
        }

        if ($j != 0) {
            if ($houseGuids) {
                $this->updateAddresses(['houseguid' => $houseGuids], false);
            }
            $transaction->commit();
            ProgressHelper::updateProgress($count, $rowsCount);
        }

        ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);

        $this->stdout("Файл $filename обработан.\nОбновлено $update записей, $insert новых записей\n");
    }

    /**
     * @param array $condition
     * @param bool $verbose
     */
    private function updateAddresses($condition = [], $verbose = true)
    {
        $query = FiasHouse::find()
            ->andWhere(['divtype' => 0])
            ->andWhere($condition);

        $count = 0;
        $fiasHouseCount = $query->count();

        if ($verbose) {
            ProgressHelper::startProgress($count, $fiasHouseCount, "Обновление адресов: ");
        }

        /* @var Country $country */
        $country = Country::findOne(['name' => 'Россия']);

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
                    $district = new District([
                        'id_city' => $country->id_country,
                        'name' => $districtName
                    ]);
                    $district->save();
                }
            }

            foreach ($fiasHouse->addrObj->parents as $parent) {
                if ($parent->aolevel == 1) {
                    if (($region = Region::findOne(['aoguid' => $parent->aoguid])) === null) {
                        $region = new Region([
                            'aoguid' => $parent->aoguid,
                            'id_country' => $country->id_country,
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
                'id_country' => $country ? $country->id_country : null,
                'id_region' => $region ? $region->id_region : null,
                'id_subregion' => $subregion ? $subregion->id_subregion : null,
                'id_city' => $city ? $city->id_city : null,
                'id_district' => $district ? $district->id_district : null,
                'id_street' => $street ? $street->id_street : null,
                'houseguid' => $fiasHouse->houseguid,
                'postalcode' => $fiasHouse->postalcode,
                'name' => $fiasHouse->houseName,
            ]);
            $address->updateAttributes(['fullname' => $address->getFullName()]);

            if ($region && $country) {
                $region->updateAttributes(['id_country' => $country->id_country]);
            }

            if ($subregion && $region) {
                $subregion->updateAttributes(['id_region' => $region->id_region]);
            }

            if ($city && ($region || $subregion)) {
                $city->updateAttributes([
                    'id_region' => $region->id_region ?? null,
                    'id_subregion' => $subregion->id_subregion ?? null,
                ]);
            }

            if ($street && ($city || $district)) {
                $street->updateAttributes([
                    'id_city' => $city->id_city ?? null,
                    'id_district' => $district->id_district ?? null,
                ]);
            }

            if ($address->save()) {
                $count++;
            }

            if ($verbose) {
                ProgressHelper::updateProgress($count, $fiasHouseCount);
            }
        }

        if ($verbose) {
            ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);
        }
    }
}
