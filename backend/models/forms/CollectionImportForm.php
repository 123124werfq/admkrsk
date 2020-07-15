<?php

namespace backend\models\forms;

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
    public $erase=false;
    public $filepath;
    public $firstRowAsName = false;
    public $columns = [];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['skip','firstRowAsName','erase'],'integer'],
            [['sheet','filepath','name'],'string'],
            [['columns'],'safe'],
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
            'erase'=>'Удалить старые записи',
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
