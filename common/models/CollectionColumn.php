<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_collection_column".
 *
 * @property int $id_column
 * @property int $id_collection
 * @property int $id_dictionary
 * @property string $name
 * @property int $type
 * @property int $show_column_admin
 * @property int $ord
 */
class CollectionColumn extends \yii\db\ActiveRecord
{
    const TYPE_INPUT = 1;
    const TYPE_INTEGER = 10;
    const TYPE_DATE = 11;
    const TYPE_DATETIME = 12;
    const TYPE_TEXTAREA = 2;
    const TYPE_SELECT = 3;
    const TYPE_RICHTEXT = 4;
    const TYPE_CHECKBOX = 5;
    const TYPE_CHECKBOXLIST = 14;
    //const TYPE_MULTISELECT = 6;
    const TYPE_MAP = 7;
    const TYPE_FILE = 8;
    const TYPE_FILES = 18;
    const TYPE_IMAGE = 9;
    const TYPE_COLLECTION = 13;
    const TYPE_RADIO = 15;
    const TYPE_ADDRESS = 16;
    const TYPE_JSON = 17;

    public static function getTypeOptions($type)
    {
        $options = [
            self::TYPE_INPUT => [
                'maxlength'=>[
                    'name'=>'Максимальная длинна',
                    'type'=>'input',
                ],
                'placeholder'=>[
                    'name'=>'Подсказка',
                    'type'=>'input',
                ],
                'type'=>[
                    'name'=>'Тип ввода',
                    'type'=>'dropdown',
                    'values'=>[
                        'text'=>"Текст",
                        'color'=>"Цвет",
                        'email'=>"Емейл",
                        'number'=>"Число",
                        'url'=>"Ссылка",
                        'datetime'=>"Дата+Время",
                        'date'=>"Дата",
                    ],
                ],
            ],
            self::TYPE_INTEGER => [
                'min'=>[
                    'name'=>'Минимум',
                    'type'=>'input',
                ],
                'max'=>[
                    'name'=>'Максимум',
                    'type'=>'input',
                ],
                'step'=>[
                    'name'=>'Шаг',
                    'type'=>'input',
                ],
            ],
            self::TYPE_TEXTAREA => [
                'maxlength'=>[
                    'name'=>'Максимальная длинна',
                    'type'=>'input',
                ],
                'rows'=>[
                    'name'=>'Высота в колонках',
                    'type'=>'input',
                ],
            ],
            self::TYPE_FILE => [
                'accept'=>[
                    'name'=>'Допустимые расширения файлов',
                    'type'=>'input',
                ],
                'filesize'=>[
                    'name'=>'Максимальный размер файла в мегабайтах',
                    'type'=>'input',
                ],
            ],
            self::TYPE_FILES => [
                'accept'=>[
                    'name'=>'Допустимые расширения файлов',
                    'type'=>'input',
                ],
                'count'=>[
                    'name'=>'Максимальное количество файлов',
                    'type'=>'input',
                ],
                'filesize'=>[
                    'name'=>'Максимальный размер файла в мегабайтах',
                    'type'=>'input',
                ],
            ],
            self::TYPE_IMAGE => [
                'accept'=>[
                    'name'=>'Допустимые расширения файлов',
                    'type'=>'input',
                ]
            ],
        ];

        if (isset($options[$type]))
            return $options[$type];

        return [];
    }

    public static function getTypeLabel($type = 0)
    {
        $labels = [
            self::TYPE_INPUT => "Строка",
            self::TYPE_INTEGER => "Число",
            self::TYPE_DATE => "Дата",
            self::TYPE_DATETIME => "Дата + Время",
            self::TYPE_TEXTAREA => "Текст",
            self::TYPE_SELECT => "Выпадающий список",
            self::TYPE_RICHTEXT => "Редактор текста",
            self::TYPE_CHECKBOX => "Чекбокс",
            self::TYPE_CHECKBOXLIST => "Чекбокс множественный",
            self::TYPE_RADIO => "Радио кнопки",
            self::TYPE_MAP => "Координаты",
            self::TYPE_FILE => "Файл",
            self::TYPE_FILES => "Файлы",
            self::TYPE_COLLECTION => "Список",
            self::TYPE_IMAGE => "Изображение",
            self::TYPE_ADDRESS => "Адрес",
            self::TYPE_JSON => "JSON",
        ];


        if (empty($type))
            return $labels;

        return $labels[$type];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_collection_column';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'name'], 'required'],
            [['id_collection', 'id_dictionary', 'type', 'show_column_admin', 'ord'], 'default', 'value' => null],
            [['id_collection', 'id_dictionary', 'type', 'show_column_admin', 'ord'], 'integer'],
            [['name','alias'], 'string', 'max' => 255],
            [['variables'], 'string'],
            [['type'], 'default', 'value' => self::TYPE_INPUT],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_column' => 'Id Column',
            'id_collection' => 'Id Collection',
            'id_dictionary' => 'Id Dictionary',
            'name' => 'Название',
            'type' => 'Тип',
            'alias' => 'Алиас',
            'show_column_admin' => 'Show Column Admin',
            'ord' => 'Ord',
        ];
    }
}
