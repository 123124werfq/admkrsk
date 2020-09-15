<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Cron\CronExpression;
use DateTime;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "db_opendata".
 *
 * @property int $id_opendata
 * @property int $id_collection
 * @property int $id_user
 * @property array $urls
 * @property string $identifier
 * @property string $title
 * @property string $description
 * @property string $owner
 * @property string $keywords
 * @property array $columns
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property string $signature
 * @property string $path
 * @property string $filename
 * @property string $url
 * @property array $metadata
 * @property string $schedule
 * @property array $schedule_settings
 * @property string[] $nextRunDates
 *
 * @property Collection $collection
 * @property Page $page
 * @property User $user
 * @property OpendataStructure[] $structures
 * @property OpendataData[] $data
 * @property OpendataData $firstData
 * @property OpendataData $lastData
 */
class Opendata extends ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Открытые данные';
    const VERBOSE_NAME_PLURAL = 'Открытые данные';
    const TITLE_ATTRIBUTE = 'title';

    const OPENDATA_LIST_PATH = 'opendata/list.csv';

    public $access_user_ids;
    public $access_user_group_ids;

    /* @var string */
    public $minutes = '*';

    /* @var array */
    public $selectedMinutes = [];

    /* @var string */
    public $hours = '*';

    /* @var array */
    public $selectedHours = [];

    /* @var string */
    public $days = '*';

    /* @var array */
    public $selectedDays = [];

    /* @var string */
    public $months = '*';

    /* @var array */
    public $selectedMonths = [];

    /* @var string */
    public $weekdays = '*';

    /* @var array */
    public $selectedWeekdays = [];

    /* @var string */
    static $path = '@console/runtime/settings';

    /* @var string */
    static $filename = '@console/runtime/settings/institution.json';

    /* @var array */
    private $_nextRunDates;

    /* @var CronExpression */
    private $_cronExpression;

    /* @var array */
    private $_defaultSetting = [
        'minutes' => 'select',
        'selectedMinutes' => ['0'],
        'hours' => 'select',
        'selectedHours' => ['0'],
        'days' => '*',
        'selectedDays' => [],
        'months' => '*',
        'selectedMonths' => [],
        'weekdays' => '*',
        'selectedWeekdays' => [],
    ];

    const MINUTES = [
        '*' => 'Каждую минуту',
        '*/2' => 'Четные минуты',
        '1-59/2' => 'Нечетные минуты',
        '*/5' => 'Каждые 5 минут',
        '*/15' => 'Каждые 15 минут',
        '*/30' => 'Каждые 30 минут',
    ];

    const HOURS = [
        '*' => 'Каждый час',
        '*/2' => 'Четные часы',
        '1-23/2' => 'Нечетные часы',
        '*/6' => 'Каждые 6 часов',
        '*/12' => 'Каждые 12 часов',
    ];

    const DAYS = [
        '*' => 'Каждый день',
        '*/2' => 'Четные дни',
        '1-31/2' => 'Нечетные дни',
        '*/5' => 'Каждые 5 дней',
        '*/10' => 'Каждые 10 дней',
        '*/15' => 'Каждые пол месяца',
    ];

    const MONTHS = [
        '*' => 'Каждый месяц',
        '*/2' => 'Четные месяцы',
        '1-11/2' => 'Нечетные месяцы',
        '*/4' => 'Каждые 4 месяца',
        '*/6' => 'Каждые пол года',
    ];

    const WEEKDAYS = [
        '*' => 'Каждый день недели',
        '1-5' => 'Понедельник-пятница',
        '0,6' => 'Выходные дни',
    ];

    const SELECTED_DAYS = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12,
        13 => 13,
        14 => 14,
        15 => 15,
        16 => 16,
        17 => 17,
        18 => 18,
        19 => 19,
        20 => 20,
        21 => 21,
        22 => 22,
        23 => 23,
        24 => 24,
        25 => 25,
        26 => 26,
        27 => 27,
        28 => 28,
        29 => 29,
        30 => 30,
        31 => 31,
    ];

    const SELECTED_MONTHS = [
        1 => 'Январь',
        2 => 'Февраль',
        3 => 'Март',
        4 => 'Апрель',
        5 => 'Май',
        6 => 'Июнь',
        7 => 'Июль',
        8 => 'Август',
        9 => 'Сентябрь',
        10 => 'Октябрь',
        11 => 'Ноябрь',
        12 => 'Декабрь',
    ];

    const SELECTED_WEEKDAYS = [
        1 => 'Понедельник',
        2 => 'Вторник',
        3 => 'Среда',
        4 => 'Четверг',
        5 => 'Пятница',
        6 => 'Суббота',
        0 => 'Воскресенье',
    ];

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
            [['identifier', 'title', 'owner'], 'required'],
            [['id_collection', 'id_user'], 'default', 'value' => null],
            [['id_collection', 'id_user'], 'integer'],
            [['description'], 'string'],
            [['urls'], 'each', 'rule' => ['url', 'enableIDN' => true]],
            [['columns'], 'safe'],
            [['identifier', 'title', 'owner', 'keywords'], 'string', 'max' => 255],
            [['id_collection'], 'exist', 'skipOnError' => true, 'targetClass' => Collection::class, 'targetAttribute' => ['id_collection' => 'id_collection']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],

            [['access_user_ids', 'access_user_group_ids'], 'each', 'rule' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            ['access_user_group_ids', 'each', 'rule' => ['exist', 'targetClass' => UserGroup::class, 'targetAttribute' => 'id_user_group']],

            [['minutes', 'hours', 'days', 'months', 'weekdays'], 'required'],
            [['minutes', 'hours', 'days', 'months', 'weekdays'], 'string'],
            [['selectedMinutes', 'selectedHours', 'selectedDays', 'selectedMonths', 'selectedWeekdays'], 'each', 'rule' => ['integer']],
            ['selectedMinutes', 'required', 'when' => function() {
                return $this->minutes == 'select';
            }, 'message' => 'Выберите минуты.'],
            ['selectedHours', 'required', 'when' => function() {
                return $this->hours == 'select';
            }, 'message' => 'Выберите часы.'],
            ['selectedDays', 'required', 'when' => function() {
                return $this->days == 'select';
            }, 'message' => 'Выберите дни.'],
            ['selectedMonths', 'required', 'when' => function() {
                return $this->months == 'select';
            }, 'message' => 'Выберите месяцы.'],
            ['selectedWeekdays', 'required', 'when' => function() {
                return $this->weekdays == 'select';
            }, 'message' => 'Выберите дни недели.'],
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
            'urls' => 'Гиперссылки (URL) на страницы сайта',
            'identifier' => 'Идентификационный номер',
            'title' => 'Наименование набора данных',
            'description' => 'Описание набора данных',
            'owner' => 'Владелец набора данных',
            'keywords' => 'Ключевые слова, соответствующие содержанию набора данных',
            'columns' => 'Поля',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Обновил',
            'deleted_at' => 'Удалено',
            'deleted_by' => 'Удалил',
            'schedule_settings' => 'Расписание обновления',
            'minutes' => 'Минуты',
            'selectedMinutes' => 'Минуты',
            'hours' => 'Часы',
            'selectedHours' => 'Часы',
            'days' => 'Дни',
            'selectedDays' => 'Дни',
            'months' => 'Месяцы',
            'selectedMonths' => 'Месяцы',
            'weekdays' => 'Дни недели',
            'selectedWeekdays' => 'Дни недели',
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
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.opendata',
            ],
        ];
    }

    public function init()
    {
        parent::init();

        $this->setAttributes($this->schedule_settings ? $this->schedule_settings : $this->_defaultSetting, [
            'minutes', 'selectedMinutes', 'hours', 'selectedHours', 'days', 'selectedDays',
            'months', 'selectedMonths', 'weekdays', 'selectedWeekdays',
        ]);
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->setAttributes($this->schedule_settings ? $this->schedule_settings : $this->_defaultSetting, [
            'minutes', 'selectedMinutes', 'hours', 'selectedHours', 'days', 'selectedDays',
            'months', 'selectedMonths', 'weekdays', 'selectedWeekdays',
        ]);
    }

    public function beforeSave($insert)
    {
        $this->schedule_settings = $this->getAttributes([
            'minutes', 'selectedMinutes', 'hours', 'selectedHours', 'days', 'selectedDays',
            'months', 'selectedMonths', 'weekdays', 'selectedWeekdays',
        ]);

        return parent::beforeSave($insert);
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
     * @return ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_collection']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStructures()
    {
        return $this->hasMany(OpendataStructure::class, ['id_opendata' => 'id_opendata']);
    }

    /**
     * @return ActiveQuery
     */
    public function getData()
    {
        return $this->hasMany(OpendataData::class, ['id_opendata_structure' => 'id_opendata_structure'])
            ->via('structures');
    }

    /**
     * @return ActiveQuery
     */
    public function getFirstData()
    {
        return $this->hasOne(OpendataData::class, ['id_opendata_structure' => 'id_opendata_structure'])
            ->via('structures')
            ->orderBy(['created_at' => SORT_ASC]);
    }

    /**
     * @return ActiveQuery
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
     * @return string|null
     */
    public function getUrl()
    {
        $url = null;

        if (Yii::$app->publicStorage->has($this->path)) {
            $url = Yii::$app->publicStorage->getPublicUrl($this->path);

            if (strpos($url, '127.0.0.1:9000') !== false) {
                return str_replace('127.0.0.1:9000', 'storage.admkrsk.ru', $url);
            }
        }

        return $url;
    }

    /**
     * @return array|null
     */
    public function getMetadata()
    {
        $metadata = null;

        if (Yii::$app->publicStorage->has($this->path)) {
            $metadata = Yii::$app->publicStorage->getMetadata($this->path);
        }

        return $metadata;
    }

    /**
     * @return string|null
     */
    public static function getListUrl()
    {
        $url = null;

        if (Yii::$app->publicStorage->has(self::OPENDATA_LIST_PATH)) {
            $url = Yii::$app->publicStorage->getPublicUrl(self::OPENDATA_LIST_PATH);

            if (strpos($url, '127.0.0.1:9000') !== false) {
                return str_replace('127.0.0.1:9000', 'storage.admkrsk.ru', $url);
            }
        }

        return $url;
    }

    /**
     * @return string|null
     */
    public static function getListMetadata()
    {
        $metadata = null;

        if (Yii::$app->publicStorage->has(self::OPENDATA_LIST_PATH)) {
            $metadata = Yii::$app->publicStorage->getMetadata(self::OPENDATA_LIST_PATH);
        }

        return $metadata;
    }

    /**
     * @return string
     */
    public function getStandardversion()
    {
        return Vars::getVar('opendata_version');
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
     * @throws InvalidConfigException
     */
    public function getCreated()
    {
        return Yii::$app->formatter->asDate($this->created_at);
    }

    /**
     * @return string
     * @throws InvalidConfigException
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
     * @throws InvalidConfigException
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
     * @return string
     */
    public function getSchedule()
    {
        if ($this->minutes == 'select') {
            $schedule = implode(',', $this->selectedMinutes) . ' ';
        } else {
            $schedule = $this->minutes . ' ';
            $this->selectedMinutes = [];
        }

        if ($this->hours == 'select') {
            $schedule .= implode(',', $this->selectedHours) . ' ';
        } else {
            $schedule .= $this->hours . ' ';
            $this->selectedHours = [];
        }

        if ($this->days == 'select') {
            $schedule .= implode(',', $this->selectedDays) . ' ';
        } else {
            $schedule .= $this->days . ' ';
            $this->selectedDays = [];
        }

        if ($this->months == 'select') {
            $schedule .= implode(',', $this->selectedMonths) . ' ';
        } else {
            $schedule .= $this->months . ' ';
            $this->selectedMonths = [];
        }

        if ($this->weekdays == 'select') {
            $schedule .= implode(',', $this->selectedWeekdays);
        } else {
            $schedule .= $this->weekdays;
            $this->selectedWeekdays = [];
        }

        return $schedule;
    }

    /**
     * @return CronExpression
     */
    public function getCronExpression()
    {
        if (!$this->_cronExpression) {
            $this->_cronExpression = CronExpression::factory($this->schedule);
        }

        return $this->_cronExpression;
    }

    /**
     * @return string|null
     */
    public function getExpression()
    {
        return $this->getCronExpression()->getExpression();
    }

    /**
     * @return bool
     */
    public function isDue()
    {
        return $this->getCronExpression()->isDue();
    }

    /**
     * @return string[]
     * @throws InvalidConfigException
     */
    public function getNextRunDates()
    {
        if (!$this->_nextRunDates) {
            foreach ($this->getCronExpression()->getMultipleRunDates(3) as $dateTime) {
                /* @var DateTime $dateTime */
                $this->_nextRunDates[] =  Yii::$app->formatter->asDatetime($dateTime->format('U'));
            }
        }

        return $this->_nextRunDates;
    }

    /**
     * @return boolean
     * @throws Exception
     */
    public function export()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->exportData();

            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @return Opendata
     * @throws InvalidConfigException
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
            fputcsv($tmpfile, [$datum->filename, $datum->url]);
        }

        foreach ($this->structures as $structure) {
            fputcsv($tmpfile, [$structure->filename, $structure->url]);
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
        if ($this->id_collection && $this->columns) {
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
        }

        return null;
    }

    /**
     * @return OpendataData|null
     */
    public function exportData()
    {
        if ($this->id_collection && $this->columns) {
            if (($opendataStructure = $this->exportStructure()) !== null) {
                $opendataData = new OpendataData(['id_opendata_structure' => $opendataStructure->id_opendata_structure]);
                if (!$opendataData->save()) {
                    return null;
                }

                $collectionColumns = CollectionColumn::find()->where(['id_column' => $this->columns])->indexBy('id_column')->all();
                $columns = ArrayHelper::getColumn($collectionColumns, 'id_column');

                $data = $this->collection->getData($columns);

                $tmpfile = tmpfile();

                fputcsv($tmpfile, $columns);

                foreach ($data as $datum) {
                    try {
                        $row = [];
                        foreach ($datum as $key => $value) {
                            $row[$key] = $collectionColumns[$key]->getValueByType($value);
                        }
                        fputcsv($tmpfile, $row);
                    } catch (\Exception $e) {
                        Yii::error([
                            'error' => $e->getMessage(),
                            'datum' => $datum,
                            'row' => $row,
                        ]);
                    }
                }

                Yii::$app->publicStorage->putStream($opendataData->path, $tmpfile);

                fclose($tmpfile);

                $this->exportMeta();

                return $opendataData;
            }
        }

        return null;
    }

    /**
     * @return string|null
     * @throws InvalidConfigException
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
                $opendata->url,
                $opendata->metadata['extension'] ?? null,
            ]);
        }

        $url = Yii::$app->publicStorage->putStream(self::OPENDATA_LIST_PATH, $tmpfile);

        fclose($tmpfile);

        return $url;
    }
}
