<?php

namespace backend\models\forms;

use Cron\CronExpression;
use DateTime;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Json;

/**
 * @property string $url
 * @property string $schedule
 * @property string $minutes
 * @property array $selectedMinutes
 * @property string $hours
 * @property array $selectedHours
 * @property string $days
 * @property array $selectedDays
 * @property string $months
 * @property array $selectedMonths
 * @property string $weekdays
 * @property array $selectedWeekdays
 * @property string[] $nextRunDates
 */
class InstitutionUpdateSettingForm extends Model
{
    /* @var $url */
    public $url;

    /* @var string */
    public $schedule;

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

    /* @var array */
    private $_nextRunDates;

    /* @var CronExpression */
    private $_cronExpression;

    /* @var array */
    private $_defaultSetting = [
        'url' => 'https://bus.gov.ru/public-rest/api/passport?id=221',
        'schedule' => '* * * * *',
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

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function init()
    {
        parent::init();

        $path = Yii::getAlias(self::$path);
        $filename = Yii::getAlias(self::$filename);

        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }

        if (is_file($filename)) {
            $settings = Json::decode(file_get_contents($filename));
        } else {
            $settings = $this->_defaultSetting;

            file_put_contents($filename, Json::encode($settings));
        }

        $this->setAttributes($settings);

        foreach ($this->getCronExpression()->getMultipleRunDates(3) as $dateTime) {
            /* @var DateTime $dateTime */
            $this->_nextRunDates[] =  Yii::$app->formatter->asDatetime($dateTime->format('U'));
        }
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
     * @return array
     */
    public function getNextRunDates()
    {
        return $this->_nextRunDates;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schedule', 'minutes', 'hours', 'days', 'months', 'weekdays'], 'required'],
            [['url', 'schedule', 'minutes', 'hours', 'days', 'months', 'weekdays'], 'string'],
            [['url'], 'url'],
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

    public function attributeLabels()
    {
        return [
            'url' => 'Ссылка на паспорт муниципальных огранизаций',
            'schedule' => 'Расписание',
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
     * @return bool
     * @throws Exception
     */
    public function save()
    {
        if ($this->validate()) {
            $path = Yii::getAlias(self::$path);
            $filename = Yii::getAlias(self::$filename);

            if ($this->minutes == 'select') {
                $this->schedule = implode(',', $this->selectedMinutes) . ' ';
            } else {
                $this->schedule = $this->minutes . ' ';
                $this->selectedMinutes = [];
            }

            if ($this->hours == 'select') {
                $this->schedule .= implode(',', $this->selectedHours) . ' ';
            } else {
                $this->schedule .= $this->hours . ' ';
                $this->selectedHours = [];
            }

            if ($this->days == 'select') {
                $this->schedule .= implode(',', $this->selectedDays) . ' ';
            } else {
                $this->schedule .= $this->days . ' ';
                $this->selectedDays = [];
            }

            if ($this->months == 'select') {
                $this->schedule .= implode(',', $this->selectedMonths) . ' ';
            } else {
                $this->schedule .= $this->months . ' ';
                $this->selectedMonths = [];
            }

            if ($this->weekdays == 'select') {
                $this->schedule .= implode(',', $this->selectedWeekdays) . ' ';
            } else {
                $this->schedule .= $this->weekdays . ' ';
                $this->selectedWeekdays = [];
            }

            if (!is_dir($path)) {
                FileHelper::createDirectory($path);
            }

            file_put_contents($filename, Json::encode($this->attributes));

            return true;
        }

        return false;
    }
}
