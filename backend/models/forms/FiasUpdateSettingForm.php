<?php

namespace backend\models\forms;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Json;

/**
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
 */
class FiasUpdateSettingForm extends Model
{
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

    /* @var array */
    private $defaultSetting = [
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

    /* @var string */
    static $path = '@console/runtime/settings';

    /* @var string */
    static $filename = '@console/runtime/settings/fias.json';

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
        'select' => 'Выберите',
    ];

    const DAYS = [
        '*' => 'Каждый день',
        '*/2' => 'Четные дни',
        '1-31/2' => 'Нечетные дни',
        '*/5' => 'Каждые 5 дней',
        '*/10' => 'Каждые 10 дней',
        '*/15' => 'Каждые пол месяца',
        'select' => 'Выберите',
    ];

    const MONTHS = [
        '*' => 'Каждый месяц',
        '*/2' => 'Четные месяцы',
        '1-11/2' => 'Нечетные месяцы',
        '*/4' => 'Каждые 4 месяца',
        '*/6' => 'Каждые пол года',
        'select' => 'Выберите',
    ];

    const WEEKDAYS = [
        '*' => 'Каждый день недели',
        '1-5' => 'Понедельник-пятница',
        '0,6' => 'Выходные дни',
        'select' => 'Выберите',
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
            $settings = $this->defaultSetting;

            file_put_contents($filename, Json::encode($settings));
        }

        $this->setAttributes($settings);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schedule', 'minutes', 'hours', 'days', 'months', 'weekdays'], 'required'],
            [['schedule', 'minutes', 'hours', 'days', 'months', 'weekdays'], 'string'],
            [['selectedMinutes', 'selectedHours', 'selectedDays', 'selectedMonths', 'selectedWeekdays'], 'each', 'rule' => ['integer']],
            ['selectedMinutes', 'required', 'when' => function() {
                return $this->minutes == 'select';
            }],
            ['selectedHours', 'required', 'when' => function() {
                return $this->hours == 'select';
            }],
            ['selectedDays', 'required', 'when' => function() {
                return $this->days == 'select';
            }],
            ['selectedMonths', 'required', 'when' => function() {
                return $this->months == 'select';
            }],
            ['selectedWeekdays', 'required', 'when' => function() {
                return $this->weekdays == 'select';
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'schedule' => 'Расписание',
            'minutes' => 'Минуты',
            'selectedMinutes' => 'Минуты',
            'hours' => 'Часы',
            'selectHours' => 'Часы',
            'days' => 'Дни',
            'selectDays' => 'Дни',
            'months' => 'Месяцы',
            'selectMonths' => 'Месяцы',
            'weekdays' => 'Дни недели',
            'selectWeekdays' => 'Дни недели',
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
            }

            if ($this->hours == 'select') {
                $this->schedule .= implode(',', $this->selectedHours) . ' ';
            } else {
                $this->schedule .= $this->hours . ' ';
            }

            if ($this->days == 'select') {
                $this->schedule .= implode(',', $this->selectedDays) . ' ';
            } else {
                $this->schedule .= $this->days . ' ';
            }

            if ($this->months == 'select') {
                $this->schedule .= implode(',', $this->selectedMonths) . ' ';
            } else {
                $this->schedule .= $this->months . ' ';
            }

            if ($this->weekdays == 'select') {
                $this->schedule .= implode(',', $this->selectedWeekdays) . ' ';
            } else {
                $this->schedule .= $this->weekdays . ' ';
            }

            echo "<pre>";
            print_r($this->attributes);
            die();

            if (!is_dir($path)) {
                FileHelper::createDirectory($path);
            }

            file_put_contents($filename, Json::encode($this->attributes));

            return true;
        }

        return false;
    }
}