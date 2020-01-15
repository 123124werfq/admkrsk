<?php

namespace common\jobs;

use common\models\Collection;
use common\models\CollectionColumn;
use common\models\CollectionRecord;
use common\models\Institution;
use Exception;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\queue\JobInterface;

class InstitutionImportJob extends BaseObject implements JobInterface
{
    /* @var string */
    static $path = '@console/runtime/queue';

    /* @var string */
    static $filename = '@console/runtime/queue/jobs.txt';

    /**
     * @return mixed|null
     */
    public static function getJobId()
    {
        $filename = Yii::getAlias(self::$filename);

        if (is_file($filename)) {
            $jobs = Json::decode(file_get_contents($filename));
        } else {
            $jobs = [];
        }

        return $jobs['InstitutionImportJob'] ?? null;
    }

    /**
     * @param string|null $jobId
     * @throws \yii\base\Exception
     */
    public static function saveJobId(?string $jobId)
    {
        $path = Yii::getAlias(self::$path);
        $filename = Yii::getAlias(self::$filename);

        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }

        if (is_file($filename)) {
            $jobs = Json::decode(file_get_contents($filename));
        } else {
            $jobs = [];
        }

        $jobs['InstitutionImportJob'] = $jobId;

        file_put_contents($filename, Json::encode($jobs));
    }

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
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
}
