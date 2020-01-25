<?php

namespace console\controllers;

use common\helpers\ProgressHelper;
use common\models\City;
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

    public function options($actionId)
    {
        return [
            'region',
        ];
    }

    /**
     * Обновление адресов
     * @throws \Exception
     */
    public function actionUpdateAddresses()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->updateAddresses();

            $transaction->commit();

            $this->stdout(Yii::t('app', 'Обновлено {count} адресов', ['count' => $count]) . PHP_EOL);
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
            ->limit(25000);

        foreach ($houseQuery->each() as $house) {
            /* @var House $house */
            $house->updateLocation();
        }
    }

    public function actionUpdate()
    {
//        $lastVersion = FiasUpdateHistory::find()->max('version');
//
//        $client = new SoapClient('https://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL');
//
//        try {
//            $response = $client->GetAllDownloadFileInfo();
//        } catch (SoapFault $exception) {
//            $updateHistory = new FiasUpdateHistory(['text' => $exception->getMessage()]);
//            $updateHistory->save();
//            echo "<pre>";
//            print_r($updateHistory->errors);
//            die();
//            exit(0);
//        }
//
//        $updates = ArrayHelper::map($response->GetAllDownloadFileInfoResult->DownloadFileInfo, 'VersionId', function (\StdClass $object) use ($lastVersion) {
//            return [
//                'version' => $object->VersionId,
//                'text' => $object->TextVersion,
//                'file' => $lastVersion ? $object->FiasDeltaDbfUrl : $object->FiasCompleteDbfUrl,
//            ];
//        });

        $lastVersion = null;

        $updates = [
            [
                'version' => 602,
                'text' => 'БД ФИАС от 23.12.2019',
                'file' => 'http://data.nalog.ru/Public/Downloads/20191223/fias_dbf.rar',
            ],
            [
                'version' => 603,
                'text' => 'БД ФИАС от 26.12.2019',
                'file' => 'http://data.nalog.ru/Public/Downloads/20191226/fias_delta_dbf.rar',
            ],
        ];

//        if ($lastVersion) {
//            foreach ($updates as $key => $update) {
//                if ($update['version'] <= $lastVersion) {
//                    unset($updates[$key]);
//                }
//            }
//        } else {
//            $updates = [array_pop($updates)];
//        }

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
//        foreach (FileHelper::findFiles($path, ['only' => ['ADDROB' . $this->region . '*']]) as $file) {
//            $this->importDbf($file);
//        }

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
        $filename = Yii::getAlias('@runtime/fias_update/' . $updateHistory->version . '_' . basename($updateHistory->file));

//        $client = new Client();
//        $client->get($updateHistory->file, ['save_to' => $filename]);

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

//        if (is_dir($path)) {
//            FileHelper::removeDirectory($path);
//        }
//
//        exec("unrar x $archive $path");

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
//                if ($houseGuids) {
//                    $this->updateAddresses(['houseguid' => $houseGuids]);
//                }
                $transaction->commit();
                $j = 0;
                $houseGuids = [];
                ProgressHelper::updateProgress($count, $rowsCount);
                $transaction = Yii::$app->db->beginTransaction();
            }
        }

        if ($j != 0) {
//            if ($houseGuids) {
//                $this->updateAddresses(['houseguid' => $houseGuids]);
//            }
            $transaction->commit();
            ProgressHelper::updateProgress($count, $rowsCount);
        }

        ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);

        $this->stdout("Файл $filename обработан. Обновлено $update записей, $insert новых записей\n");
    }

    /**
     * @param array $condition
     */
    private function updateAddresses($condition = [])
    {
        $query = FiasHouse::find()
            ->andWhere(['divtype' => 0])
            ->andWhere($condition);

        $count = 0;
        $fiasHouseCount = $query->count();

        Region::deleteAll(['is_manual' => false]);
        Subregion::deleteAll(['is_manual' => false]);
        City::deleteAll(['is_manual' => false]);
        District::deleteAll(['is_manual' => false]);
        Street::deleteAll(['is_manual' => false]);
        House::deleteAll(['is_manual' => false]);

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
            ]);
            $address->updateAttributes(['fullname' => $address->getFullName()]);

            if ($address->save()) {
                $count++;
            }

            ProgressHelper::updateProgress($count, $fiasHouseCount);
        }
        ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);
    }
}