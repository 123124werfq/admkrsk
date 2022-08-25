<?php

namespace common\jobs;

use common\base\Job;
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
use Throwable;
use Exception;
use Yii;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\queue\RetryableJobInterface;

class FiasImportJob extends Job implements RetryableJobInterface
{
    public $region = 24;

    private $_lastVersion;

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function execute($queue)
    {
        $this->_lastVersion = FiasUpdateHistory::find()->max('version');

        //$client = new SoapClient('https://fias.nalog.ru/WebServices/Public/DownloadService.asmx?WSDL');

        try {
            /*$response = $client->GetAllDownloadFileInfo();

            $updates = ArrayHelper::map($response->GetAllDownloadFileInfoResult->DownloadFileInfo, 'VersionId', function (\StdClass $object) {
                return [
                    'version' => $object->VersionId,
                    'text' => $object->TextVersion,
                    'file' => $this->_lastVersion ? $object->FiasDeltaDbfUrl : $object->FiasCompleteDbfUrl,
                ];
            });
            ArrayHelper::multisort($updates, 'version');

            if ($this->_lastVersion) {
                foreach ($updates as $key => $update) {
                    if ($update['version'] <= $this->_lastVersion) {
                        unset($updates[$key]);
                    }
                }
            } else {
                $updates = [array_pop($updates)];
            }
    
            foreach ($updates as $update) {*/
                $this->fiasUpdate('');//$update);
            //}
        } catch (SoapFault $exception) {
            $updateHistory = new FiasUpdateHistory(['text' => $exception->getMessage()]);
            $updateHistory->save();
            exit(0);
        } catch (Exception $exception) {
            $updateHistory = new FiasUpdateHistory(['text' => $exception->getMessage()]);
            $updateHistory->save();
            exit(0);
        } catch (Throwable $exception) {
            $updateHistory = new FiasUpdateHistory(['text' => $exception->getMessage()]);
            $updateHistory->save();
            exit(0);
        }
    }

    /**
     * @return int time to reserve in seconds
     */
    public function getTtr()
    {
        return 60 * 60 * 3;
    }

    /**
     * @param int $attempt number
     * @param Exception|Throwable $error from last execute of the job
     * @return bool
     */
    public function canRetry($attempt, $error)
    {
        return false;
    }

    /**
     * @param $update
     * @throws ErrorException
     * @throws \yii\base\Exception
     */
    private function fiasUpdate($update)
    {
        /*$updateHistory = new FiasUpdateHistory($update);

        FileHelper::createDirectory(Yii::getAlias('@runtime/fias_update'));

        $file = $this->downloadFile($updateHistory);

        $path = $this->extractFile($file);
*/
        $path = Yii::getAlias('@runtime/fias_update');
        $this->updateData($path);

        //$updateHistory->save();
    }

    /**
     * @param $path
     */
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
        if ($this->_lastVersion) {
            $filename .= '_fias_delta_dbf.zip';
        } else {
            $filename .= '_fias_dbf.zip';
        }

        $client = new Client();
        $client->get($updateHistory->file, ['save_to' => $filename]);

        return $filename;
    }

    /**
     * @param string $archive
     * @return string
     * @throws ErrorException
     */
    private function extractFile($archive)
    {
        $path = Yii::getAlias('@runtime/fias_update/' . basename($archive, '.zip') . DIRECTORY_SEPARATOR);

        if (is_dir($path)) {
            FileHelper::removeDirectory($path);
        }

        exec("unzip $archive ADDROB{$this->region}.DBF HOUSE{$this->region}.DBF -d $path");

        return $path;
    }

    /**
     * @param $filename
     * @return int
     * @throws \yii\db\Exception
     */
    private function importDbf($filename)
    {
        if (!$db = @dbase_open($filename, 0)) {
            Console::stderr("Не удалось открыть DBF файл: '$filename'\n");
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
            Console::stderr("Не поддерживаемый DBF файл: '$filename'\n");
            return 1;
        }

        $rowsCount = dbase_numrecords($db);
        Console::stdout("Записей в DBF файле '$filename': $rowsCount\n");

        $transaction = Yii::$app->db->beginTransaction();

        $houseGuids = [];
        $count = $insert = $update = $j = 0;

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
                $model->{$key} = trim(mb_convert_encoding($value, 'UTF-8', 'CP866')) ?: null;
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
                    $this->updateAddresses(['houseguid' => $houseGuids]);
                }
                $houseGuids = [];
                $transaction->commit();
                $j = 0;
                $transaction = Yii::$app->db->beginTransaction();
            }
        }

        if ($j != 0) {
            if ($houseGuids) {
                $this->updateAddresses(['houseguid' => $houseGuids]);
            }
            $transaction->commit();
        }

        Console::stdout("Файл $filename обработан.\nОбновлено $update записей, $insert новых записей\n");
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
        }
    }
}
