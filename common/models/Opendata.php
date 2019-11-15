<?php

namespace common\models;

use common\modules\log\behaviors\LogBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "db_opendata".
 *
 * @property int $id_opendata
 * @property int $id_collection
 * @property int $id_user
 * @property int $id_page
 * @property string $identifier
 * @property string $title
 * @property string $description
 * @property string $owner
 * @property string $url
 * @property string $keywords
 * @property array $columns
 * @property int $period
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property string $signature
 * @property string $path
 * @property string $filename
 *
 * @property Collection $collection
 * @property Page $page
 * @property User $user
 * @property OpendataStructure[] $structures
 * @property OpendataData[] $data
 * @property OpendataData $firstData
 * @property OpendataData $lastData
 */
class Opendata extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Открытые данные';
    const VERBOSE_NAME_PLURAL = 'Открытые данные';
    const TITLE_ATTRIBUTE = 'title';

    const VERSION = 'http://data.gov.ru/metodicheskie-rekomendacii-po-publikacii-otkrytyh-dannyh-versiya-30';
    const OPENDATA_LIST_PATH = 'opendata/list.csv';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_opendata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'id_user', 'identifier', 'title', 'owner', 'columns', 'period'], 'required'],
            [['id_collection', 'id_user', 'id_page', 'period'], 'default', 'value' => null],
            [['id_collection', 'id_user', 'id_page', 'period'], 'integer'],
            [['description'], 'string'],
            [['columns'], 'safe'],
            [['identifier', 'title', 'owner', 'keywords'], 'string', 'max' => 255],
            [['id_collection'], 'exist', 'skipOnError' => true, 'targetClass' => Collection::class, 'targetAttribute' => ['id_collection' => 'id_collection']],
            [['id_page'], 'exist', 'skipOnError' => true, 'targetClass' => Page::class, 'targetAttribute' => ['id_page' => 'id_page']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_opendata' => '#',
            'id_collection' => 'Список',
            'id_user' => 'Ответственное лицо',
            'id_page' => 'Гиперссылки (URL) на страницы сайта',
            'identifier' => 'Идентификационный номер',
            'title' => 'Наименование набора данных',
            'description' => 'Описание набора данных',
            'owner' => 'Владелец набора данных',
            'keywords' => 'Ключевые слова, соответствующие содержанию набора данных',
            'columns' => 'Поля',
            'period' => 'Период обновления',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Обновил',
            'deleted_at' => 'Удалено',
            'deleted_by' => 'Удалил',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->exportData();
        }

        $this->exportMeta();

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_collection']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::class, ['id_page' => 'id_page']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructures()
    {
        return $this->hasMany(OpendataStructure::class, ['id_opendata' => 'id_opendata']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasMany(OpendataData::class, ['id_opendata_structure' => 'id_opendata_structure'])
            ->via('structures');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFirstData()
    {
        return $this->hasOne(OpendataData::class, ['id_opendata_structure' => 'id_opendata_structure'])
            ->via('structures')
            ->orderBy(['created_at' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastData()
    {
        return $this->hasOne(OpendataData::class, ['id_opendata_structure' => 'id_opendata_structure'])
            ->via('structures')
            ->orderBy(['created_at' => SORT_DESC]);
    }

    /**
     * @return string
     */
    public function getSignature()
    {
        return serialize($this->getAttributes(['id_opendata', 'columns']));
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'opendata/' . $this->identifier . '/' . $this->filename . '.csv';
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return 'meta';
    }

    /**
     * @return string
     */
    public function getStandardversion()
    {
        return self::VERSION;
    }

    /**
     * @return string
     */
    public function getCreator()
    {
        return $this->owner;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return Yii::$app->formatter->asDate($this->created_at);
    }

    /**
     * @return string
     */
    public function getModified()
    {
        return Yii::$app->formatter->asDate($this->getData()->max('created_at'));
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->keywords;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return 'csv';
    }

    /**
     * @return string
     */
    public function getValid()
    {
        return Yii::$app->formatter->asDate('+1 month');
    }

    /**
     * @return string
     */
    public function getPublishername()
    {
        return $this->user->adinfo->name ?? null;
    }

    /**
     * @return string
     */
    public function getPublisherphone()
    {
        return $this->user->adinfo->telephone_number ?? null;
    }

    /**
     * @return string
     */
    public function getPublishermbox()
    {
        return $this->user->email ?? null;
    }

    /**
     * @return boolean
     */
    public function export()
    {
        // добавить проверку расписания
        if (1) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $this->exportData();

                $transaction->commit();

                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        return false;
    }

    /**
     * @return Opendata
     */
    public function exportMeta()
    {
        $attributes = $this->getAttributes(['standardversion', 'identifier', 'title', 'description', 'creator', 'created', 'modified', 'subject', 'format', 'valid', 'publishername', 'publisherphone', 'publishermbox']);

        $tmpfile = tmpfile();

        fputcsv($tmpfile, ['property', 'value']);

        foreach ($attributes as $key => $attribute) {
            fputcsv($tmpfile, [$key, $attribute]);
        }

        foreach ($this->data as $datum) {
            fputcsv($tmpfile, [$datum->filename, Yii::$app->publicStorage->getPublicUrl($datum->path)]);
        }

        foreach ($this->structures as $structure) {
            fputcsv($tmpfile, [$structure->filename, Yii::$app->publicStorage->getPublicUrl($structure->path)]);
        }

        Yii::$app->publicStorage->putStream($this->path, $tmpfile);

        fclose($tmpfile);

        self::exportList();

        return $this;
    }

    /**
     * @return OpendataStructure|null
     */
    public function exportStructure()
    {
        $columns = CollectionColumn::findAll($this->columns);

        if ($columns) {
            if (($opendataStructure = OpendataStructure::findOne(['signature' => $this->signature])) === null) {
                $opendataStructure = new OpendataStructure([
                    'id_opendata' => $this->id_opendata,
                    'signature' => $this->signature,
                ]);

                if (!$opendataStructure->save()) {
                    return null;
                }

                $data[] = [
                    'field name',
                    'english description',
                    'russian description',
                    'format',
                ];

                foreach ($columns as $column) {
                    $data[] = [
                        $column->id_column,
                        $column->name,
                        $column->name,
                        'string',
                    ];
                }

                $tmpfile = tmpfile();

                foreach ($data as $datum) {
                    fputcsv($tmpfile, $datum);
                }

                Yii::$app->publicStorage->putStream($opendataStructure->path, $tmpfile);

                fclose($tmpfile);
            }

            return $opendataStructure;
        }

        return null;
    }

    /**
     * @return OpendataData|null
     */
    public function exportData()
    {
        if (($opendataStructure = $this->exportStructure()) !== null) {
            $opendataData = new OpendataData(['id_opendata_structure' => $opendataStructure->id_opendata_structure]);
            if (!$opendataData->save()) {
                return null;
            }

            $columns = ArrayHelper::getColumn(CollectionColumn::findAll($this->columns), 'id_column');

            $data = $this->collection->getData($columns);

            $tmpfile = tmpfile();

            fputcsv($tmpfile, $columns);

            foreach ($data as $datum) {
                fputcsv($tmpfile, $datum);
            }

            Yii::$app->publicStorage->putStream($opendataData->path, $tmpfile);

            fclose($tmpfile);

            $this->exportMeta();

            return $opendataData;
        }

        return null;
    }

    /**
     * @return string|null
     */
    public static function exportList()
    {
        $tmpfile = tmpfile();

        fputcsv($tmpfile, ['property', 'title', 'value', 'format']);
        fputcsv($tmpfile, ['standardversion', 'Версия методических рекомендаций', 'http://opendata.gosmonitor.ru/standard/3.0', null]);

        foreach (self::find()->each() as $opendata) {
            /* @var Opendata $opendata */
            fputcsv($tmpfile, [
                $opendata->identifier,
                $opendata->title,
                Yii::$app->publicStorage->getPublicUrl($opendata->path),
                'csv',
            ]);
        }

        $url = Yii::$app->publicStorage->putStream(self::OPENDATA_LIST_PATH, $tmpfile);

        fclose($tmpfile);

        return $url;
    }
}
