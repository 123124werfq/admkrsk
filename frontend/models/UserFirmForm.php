<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class UserFirmForm extends Model
{
    /**
     * @var UploadedFile|Null file attribute
     */
    public $inn;
    public $ogrn;
    public $name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['inn'], 'integer'],
            [['ogrn','name'], 'string'],
            ['name', 'required'],
        ];
    }

     public function attributeLabels()
    {
        return [
            'inn' => 'ИНН',
            'name' => 'Название организации',
            'ogrn' => 'ОГРН',
        ];
    }
}