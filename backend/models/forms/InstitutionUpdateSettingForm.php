<?php

namespace backend\models\forms;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\Json;

class InstitutionUpdateSettingForm extends Model
{
    /* @var string */
    public $schedule;

    /* @var array */
    private $defaultSetting = [
        'schedule' => '0 0 * * *',
    ];

    /* @var string */
    static $path = '@console/runtime/settings';

    /* @var string */
    static $filename = '@console/runtime/settings/institution.json';

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
            ['schedule', 'required'],
            ['schedule', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'schedule' => 'Расписание',
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

            if (!is_dir($path)) {
                FileHelper::createDirectory($path);
            }

            file_put_contents($filename, Json::encode($this->attributes));

            return true;
        }

        return false;
    }
}