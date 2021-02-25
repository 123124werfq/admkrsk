<?php

namespace common\jobs;

use backend\models\forms\InstitutionUpdateSettingForm;
use common\base\Job;
use common\models\Collection;
use common\models\CollectionColumn;
use common\models\CollectionRecord;
use common\models\Institution;
use Exception;
use Yii;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\httpclient\Client;
use yii\queue\RetryableJobInterface;

class InstitutionImportJob extends Job implements RetryableJobInterface
{
    /**
     * @inheritdoc
     * @throws Exception
     */
    public function execute($queue)
    {
        return true;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $client = new Client();
            $institutionConfig = new InstitutionUpdateSettingForm();

            $response = $client->createRequest()
                ->setUrl($institutionConfig->url)
                ->addHeaders(['User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36'])
                ->send();

            if ($response->isOk) {
                $institution_version = Yii::$app->cache->get('institution_version');
                $passport = Json::decode($response->getContent());

                if (true || $passport['version'] > $institution_version) {
                    $archiveUrl = "https://bus.gov.ru/public-rest/api/opendata/{$passport['passCode']}/{$passport['actualDocument']['fileName']}";
                    $path = Yii::getAlias('@console/runtime/institutions');
                    $archive = Yii::getAlias('@console/runtime/institutions/data.zip');

                    FileHelper::removeDirectory($path);
                    FileHelper::createDirectory($path);

                    $this->downloadFile($archiveUrl, $archive);

                    exec("unzip -o $archive -x -d $path");

                    $files = FileHelper::findFiles($path, ['only' => ['*.xml']]);

                    $count = $updateCount = 0;
                    if ($files) {
                        foreach ($files as $file) {
                            $institution = Institution::updateOrCreate($file);

                            if ($institution) {
                                $updateCount++;
                            }
                            $count++;
                            unlink($file);
                        }
                    }

                    Yii::$app->cache->set('institution_version', $passport['version']);

                    Console::stdout(Yii::t('app', 'Обработано {count} организаций', ['count' => $count]) . PHP_EOL);
                    Console::stdout(Yii::t('app', 'Добавлено/обновлено {updateCount} организаций', ['updateCount' => $updateCount]) . PHP_EOL);
                } else {
                    Console::stdout(Yii::t('app', 'Нет обновлений') . PHP_EOL);
                }
            } else {
                Console::stdout(Yii::t('app', 'Паспорт муниципальных организаций не найден') . PHP_EOL);
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        $count = 0;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $institution = new Institution();
            $columns = $institution->getAttributes(null, [
                'id_institution',
                'created_at',
                'created_by',
                'updated_at',
                'updated_by',
                'deleted_at',
                'deleted_by',
            ]);

            if (($collection = Collection::findOne(['alias' => 'institution'])) === null) {
                $collection = new Collection([
                    'name' => 'Организации',
                    'alias' => 'institution',
                    'is_dictionary' => 1,
                ]);
                $collection->save();
            }

            $collectionColumns = $collection->getColumns()->indexBy('alias')->all();

            foreach ($institution->rules() as $rule) {

                foreach ($rule[0] as $attribute) {
                    switch ($rule[1]) {
                        case 'string':
                            $type = 'string';
                            break;
                        case 'integer':
                            $type = 'integer';
                            break;
                        case 'boolean':
                            $type = 'boolean';
                            break;
                        case 'safe':
                            $type = 'json';
                            break;
                        default:
                            $type = null;
                            break;
                    }

                    if (!isset($columns[$attribute])) {
                        $columns[$attribute] = $type;
                    }
                }
            }

            foreach ($columns as $column => $type) {
                if (!isset($collectionColumns[$column])) {
                    switch ($type) {
                        case 'integer':
                            $columnType = CollectionColumn::TYPE_INTEGER;
                            break;
                        case 'boolean':
                            $columnType = CollectionColumn::TYPE_CHECKBOX;
                            break;
                        case 'json':
                            $columnType = CollectionColumn::TYPE_JSON;
                            break;
                        default:
                            $columnType = CollectionColumn::TYPE_INPUT;
                            break;
                    }

                    if (in_array($column, ['last_update', 'modified_at'])) {
                        $columnType = CollectionColumn::TYPE_DATETIME;
                    }

                    $collectionColumn = new CollectionColumn([
                        'id_collection' => $collection->id_collection,
                        'alias' => $column,
                        'name' => $institution->getAttributeLabel($column),
                        'type' => $columnType,
                    ]);
                    $collectionColumn->save();

                    $collectionColumns[$column] = $collectionColumn;
                }
            }

            foreach ($collection->getData([], true) as $record) {
                if (isset($record['is_updating']) && isset($record['bus_id'])) {
                    Institution::updateAll(['is_updating' => (boolean) $record['is_updating']], ['bus_id' => $record['bus_id']]);
                }
            }

            foreach (Institution::find()->each() as $institution) {
                $attributes = $institution->getAttributes(null, [
                    'id_institution',
                    'created_at',
                    'created_by',
                    'updated_at',
                    'updated_by',
                    'deleted_at',
                    'deleted_by',
                ]);

                if ($institution->is_updating) {
                    $collectionRecord = $institution->record;

                    if (!$collectionRecord || $collectionRecord->id_collection != $collection->id_collection) {
                        $collectionRecord = new CollectionRecord;
                        $collectionRecord->id_collection = $collection->id_collection;
                        $collectionRecord->ord = CollectionRecord::find()->where(['id_collection' => $collection->id_collection])->max('ord') + 1;
                    }

                    foreach ($attributes as $attribute => $value) {
                        $collectionRecord->data[$collectionColumns[$attribute]->id_column] = !is_array($value) ? $value : Json::encode($value);
                    }

                    if ($collectionRecord->save()) {
                        $institution->link('record', $collectionRecord);
                        $count++;
                    }
                }
            }

            $transaction->commit();

            Console::stdout(Yii::t('app', 'Добавлено/обновлено {count} организаций', ['count' => $count]) . PHP_EOL);
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * @param string $url
     * @param string $dest
     */
    public function downloadFile($url, $dest)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_FILE => fopen($dest, 'w'),
            CURLOPT_TIMEOUT => 28800,
            CURLOPT_URL => $url
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @return int time to reserve in seconds
     */
    public function getTtr()
    {
        return 60 * 60;
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
}
