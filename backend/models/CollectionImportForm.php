<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class CollectionImportForm extends Model
{
    public $file;
    public $sheet;
    public $skip = '';
    public $keyrow = '';
    public $name;
    public $filepath;
    public $firstRowAsName = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['skip','firstRowAsName'],'integer'],
            [['sheet','filepath','name'],'string'],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,csv,xlsx'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'file'=>'Файл',
            'skip'=>'Начать со строки',
            'keyrow'=>'Взять ключи с',
            'firstRowAsName'=>'Использовать первую строку как названия столбцов',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
}
