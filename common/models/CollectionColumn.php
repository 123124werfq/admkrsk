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
    const TYPE_DISTRICT = 23;
    const TYPE_REGION = 19;
    const TYPE_CITY = 20;
    const TYPE_STREET = 21;
    const TYPE_HOUSE = 22;
    const TYPE_SUBREGION = 24;

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
            self::TYPE_REGION => 'Регион',
            self::TYPE_SUBREGION => 'Область',
            self::TYPE_CITY => 'Город',
            self::TYPE_DISTRICT => 'Район города',
            self::TYPE_STREET => 'Улица',
            self::TYPE_HOUSE => 'Дом',
        ];

        if (empty($type))
            return $labels;

        return $labels[$type];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert && !empty($changedAttributes['type']))
        {
            if ($changedAttributes['type'] == CollectionColumn::TYPE_DATE || $changedAttributes['type'] == CollectionColumn::TYPE_DATETIME)
            {
                $values = Yii::$app->db->createCommand("SELECT * FROM db_collection_value WHERE id_column = $column->id_column")->queryAll();

                $collection = Yii::$app->mongodb->getCollection('collection'.$model->id_collection);

                foreach ($values as $key => $value)
                {
                    if (!is_numeric($value['value']))
                    {
                        Yii::$app->db->createCommand()->update('db_collection_value',['value'=>strtotime($value['value'])],['id_column'=>$column->id_column,'id_record'=>$value['id_record']])->execute();

                        $collection->update(['id_record'=>$value['id_record']],[$value['id_column']=>strtotime($value['value'])]);
                    }
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
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
            [['id_collection', 'name', 'type'], 'required'],
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
            'id_column' => '#',
            'id_collection' => 'Список',
            'name' => 'Название',
            'type' => 'Тип',
            'alias' => 'Алиас',
            'show_column_admin' => 'Show Column Admin',
            'ord' => 'Ord',
        ];
    }
}
