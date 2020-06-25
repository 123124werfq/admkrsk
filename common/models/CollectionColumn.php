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
 * @property string $alias
 */
class CollectionColumn extends \yii\db\ActiveRecord
{
    public $is_link; // для отображения таблиц в хранении данных не учавствует

    //public $values = []; // array of values for dropdown search

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
    //const TYPE_FILES = 18;
    const TYPE_IMAGE = 9;
    const TYPE_COLLECTION = 13;
    const TYPE_COLLECTIONS = 25;
    const TYPE_RADIO = 15;
    const TYPE_ADDRESS = 16;
    const TYPE_JSON = 17;
    const TYPE_DISTRICT = 23;
    const TYPE_REGION = 19;
    const TYPE_CITY = 20;
    const TYPE_STREET = 21;
    const TYPE_HOUSE = 22;
    const TYPE_SUBREGION = 24;
    const TYPE_SERVICETARGET = 26;
    const TYPE_SERVICE = 27;
    const TYPE_CUSTOM = 28;
    const TYPE_FILE_OLD = 29;
    const TYPE_ADDRESSES = 30;
    const TYPE_REPEAT = 31;
    const TYPE_ARCHIVE = 32;

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
                        'time'=>"Время",
                        'datetime'=>"Дата+Время",
                        'date'=>"Дата",
                    ],
                ],
                'id'=>[
                    'name'=>'ID',
                    'type'=>'input',
                ],
            ],
            self::TYPE_COLLECTIONS => [
                'accept_add'=>[
                    'name'=>'Разрешить добавление',
                    'type'=>'dropdown',
                    'values'=>[
                        '0'=>"Нет",
                        '1'=>"Да",
                    ]
                ],
                'sortable'=>[
                    'name'=>'Сортировка элементов',
                    'type'=>'checkbox',
                ],
            ],
            self::TYPE_CHECKBOX => [
                'popup'=>[
                    'name'=>'Трубется дополнительное подтверждение',
                    'type'=>'dropdown',
                    'values'=>[
                        '0'=>"Нет",
                        '1'=>"Да",
                    ]
                ],
                'terms'=>[
                    'name'=>'Соглашение',
                    'type'=>'richtext',
                ],
            ],
            /*self::TYPE_REPEAT => [
                'begin'=>[
                    'name'=>'Дата начала',
                    'type'=>'input',
                ],
                'end'=>[
                    'name'=>'Дата конца',
                    'type'=>'input',
                ],
                'is_repeat'=>[
                    'name'=>'Повторяющееся событие',
                    'type'=>'checkbox',
                ],
                'repeat_count'=>[
                    'name'=>'Число повторов',
                    'type'=>'input',
                ],
                'time_begin'=>[
                    'name'=>'Время начала',
                    'type'=>'time',
                ],
                'time_end'=>[
                    'name'=>'Время конца',
                    'type'=>'time',
                ],
                'type'=>[
                    'name'=>'Трубется дополнительное подтверждение',
                    'type'=>'radio',
                    'values'=>[
                        '1'=>"Ежедневно",
                        '2'=>"Еженедельно",
                        '3'=>"Ежемесячно",
                    ]
                ],
            ],*/
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
            self::TYPE_ADDRESS => [
                'show_country'=>[
                    'name'=>'Показывать страну',
                    'type'=>'checkbox',
                ],
                'show_region'=>[
                    'name'=>'Показывать регион',
                    'type'=>'checkbox',
                ],
                'show_subregion'=>[
                    'name'=>'Показывать область',
                    'type'=>'checkbox',
                ],
                'show_city'=>[
                    'name'=>'Показывать город',
                    'type'=>'checkbox',
                ],
                'show_district'=>[
                    'name'=>'Показывать район',
                    'type'=>'checkbox',
                ],
                'show_street'=>[
                    'name'=>'Показывать улицу',
                    'type'=>'checkbox',
                ],
                'show_house'=>[
                    'name'=>'Показывать дом',
                    'type'=>'checkbox',
                ],
                'show_room'=>[
                    'name'=>'Показывать квартиру',
                    'type'=>'checkbox',
                ],
                'show_postcode'=>[
                    'name'=>'Показывать индекс',
                    'type'=>'checkbox',
                ],
                'show_coord'=>[
                    'name'=>'Показывать координаты',
                    'type'=>'checkbox',
                ],

                /*'valid_country'=>[
                    'name'=>'Обязательно страна',
                    'type'=>'checkbox',
                ],
                'valid_region'=>[
                    'name'=>'Обязательно регион',
                    'type'=>'checkbox',
                ],
                'valid_subregion'=>[
                    'name'=>'Обязательно область',
                    'type'=>'checkbox',
                ],
                'valid_city'=>[
                    'name'=>'Обязательно город',
                    'type'=>'checkbox',
                ],
                'valid_district'=>[
                    'name'=>'Обязательно район',
                    'type'=>'checkbox',
                ],*/
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
                'acceptedFiles'=>[
                    'name'=>'Допустимые расширения файлов',
                    'type'=>'input',
                ],
                'filesize'=>[
                    'name'=>'Максимальный размер файла в мегабайтах',
                    'type'=>'input',
                ],
                'maxFiles'=>[
                    'name'=>'Количество файлов',
                    'type'=>'input',
                ],
                'pagecount'=>[
                    'name'=>'Вводить количество страниц',
                    'type'=>'checkbox',
                ],
            ],
            self::TYPE_IMAGE => [
                'acceptedFiles'=>[
                    'name'=>'Допустимые расширения файлов',
                    'type'=>'input',
                ],
                'filesize'=>[
                    'name'=>'Максимальный размер файла в мегабайтах',
                    'type'=>'input',
                ],
                'maxFiles'=>[
                    'name'=>'Количество файлов',
                    'type'=>'input',
                ],
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
            self::TYPE_RICHTEXT => "Текст с редактором",

            self::TYPE_SELECT => "Выпадающий список",

            self::TYPE_CHECKBOX => "Чекбокс",
            self::TYPE_CHECKBOXLIST => "Чекбокс множественный",
            self::TYPE_RADIO => "Радио кнопки",

            self::TYPE_FILE => "Файл",
            self::TYPE_FILE_OLD => "Файл (старый формат для импорта)",

            self::TYPE_IMAGE => "Изображение",

            self::TYPE_COLLECTION => "Данные из списка",
            self::TYPE_COLLECTIONS => "Данные из списка, несколько элементов",

            self::TYPE_JSON => "Таблицы",

            self::TYPE_MAP => "Координаты",
            self::TYPE_ADDRESS => "Адрес",
            self::TYPE_ADDRESSES => "Несколько адресов",
            self::TYPE_REGION => 'Регион',
            self::TYPE_SUBREGION => 'Область',
            self::TYPE_CITY => 'Город',
            self::TYPE_DISTRICT => 'Район города',
            self::TYPE_STREET => 'Улица',
            self::TYPE_HOUSE => 'Дом',

            self::TYPE_SERVICETARGET => "Цель муниципальной услуги",
            self::TYPE_SERVICE => "Услуги для обжалования",

            self::TYPE_REPEAT => 'Дата / Период',
            self::TYPE_CUSTOM => 'Составная колонка',
        ];

        if (empty($type))
            return $labels;

        return $labels[$type];
    }

    public function isCustom()
    {
        return $this->type == self::TYPE_CUSTOM;
    }

    public function isRelation()
    {
        return ($this->type == self::TYPE_COLLECTIONS || $this->type == self::TYPE_COLLECTION);
    }

    public static function renderCustomValue($template,$data)
    {
        $value = '';

        try {
            $loader = new \Twig\Loader\ArrayLoader([
                'template' => $template,
            ]);
            $twig = new \Twig\Environment($loader);
            $value = $twig->render('template', $data);

            unset($loader);
            unset($twig);
        }
        catch (Exception $e) {

        }

        return $value;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert && !empty($changedAttributes['type']))
        {
            if ($this->type == CollectionColumn::TYPE_DATE || $this->type == CollectionColumn::TYPE_DATETIME)
            {
                $values = Yii::$app->db->createCommand("SELECT * FROM db_collection_value WHERE id_column = $this->id_column")->queryAll();

                $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);

                foreach ($values as $key => $value)
                {
                    if (!is_numeric($value['value']))
                    {
                        Yii::$app->db->createCommand()->update('db_collection_value',[
                            'value'=>strtotime($value['value'])],
                            [
                                'id_column'=>$this->id_column,
                                'id_record'=>$value['id_record']
                            ]
                        )->execute();

                        $collection->update(
                            ['id_record'=>$value['id_record']],
                            ['col'.$this->id_column=>strtotime($value['value'])]
                        );
                    }
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function getJsonQuery()
    {
        $type = 'string';

        $operators = [
           'equal',
           'not_equal',
           'in',
           'not_in',
           'less',
           'less_or_equal',
           'greater',
           'greater_or_equal',
           'between',
           'not_between',
           'begins_with',
           'not_begins_with',
           'contains',
           'not_contains',
           'ends_with',
           'not_ends_with',
           'is_empty',
           'is_not_empty',
           'is_null',
           'is_not_null'
        ];

        switch ($this->type) {
            case self::TYPE_DATETIME:
                $type = 'datetime';
                $operators = [
                   'equal',
                   'not_equal',
                   'in',
                   'not_in',
                   'less',
                   'less_or_equal',
                   'greater',
                   'greater_or_equal',
                   'between',
                   'not_between',
                   'is_empty',
                   'is_not_empty',
                ];
                break;
            case self::TYPE_DATE:
                $type = 'date';
                $operators = [
                   'equal',
                   'not_equal',
                   'in',
                   'not_in',
                   'less',
                   'less_or_equal',
                   'greater',
                   'greater_or_equal',
                   'between',
                   'not_between',
                   'is_empty',
                   'is_not_empty',
                ];
                break;
            case self::TYPE_INTEGER:
                $type = 'integer';
                $operators = [
                   'equal',
                   'not_equal',
                   'in',
                   'not_in',
                   'less',
                   'less_or_equal',
                   'greater',
                   'greater_or_equal',
                   'between',
                   'not_between',
                   'is_empty',
                   'is_not_empty',
                   'is_null',
                   'is_not_null'
                ];
                break;
            case self::TYPE_COLLECTIONS:
                $operators = ['is_null', 'is_not_null','contains','not_contains'];
                break;
            case self::TYPE_CHECKBOX:
                $operators = [
                    'is_empty',
                    'is_not_empty',
                ];
                break;
            case self::TYPE_FILE:
            case self::TYPE_IMAGE:
                $operators = [
                    'is_empty',
                    'is_not_empty',
               ];
                break;
        }

        $json = [
            'id' =>"col{$this->id_column}",
            'label'=> $this->name,
            'type'=> $type,
            'input'=> 'text',
            'operators'=> $operators,
        ];

        return $json;
    }

    public function getOptionsData()
    {
        $options = [
            'width'=>[
                'name'=>'Ширина',
                'type'=>'number',
                'value'=>'100',
                'min'=>50,
            ],
        ];

        $data = $this->options;

        foreach ($options as $key => $value)
        {
            if (!empty($data[$key]))
                $options[$key]['value'] = $data[$key];
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_collection_column';
    }

    public function isFile()
    {
        return ($this->type == self::TYPE_FILE || $this->type == self::TYPE_FILE_OLD);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'name', 'type', 'alias'], 'required'],
            [['id_collection', 'id_dictionary', 'type', 'show_column_admin', 'ord'], 'default', 'value' => null],
            [['id_collection', 'id_dictionary', 'type', 'show_column_admin', 'ord', 'protected'], 'integer'],
            [['keep_relation'], 'boolean'],
            [['name','alias'], 'string', 'max' => 500],
            [['variables'], 'string'],
            [['options','template'], 'safe'],
            [['type'], 'default', 'value' => self::TYPE_INPUT],
        ];
    }

    public function getValueByType($value)
    {
        if (empty($value))
        {
            if (is_array($value))
                $value = '';

            return $value;
        }


        /*class ValueClass
        {
            public $value;

            public function __construct($value)
            {
                $this->foo = $value;
            }

            public function __toString()
            {
                $value = $this->$value;

                case self::TYPE_DATE:
                    return date('d.m.Y',$value);
                    break;
                case self::TYPE_DATETIME:
                    return date('d.m.Y H:i',$value);
                    break;
                case self::TYPE_DISTRICT:
                    $model = District::findOne((int)$value);
                    return $model->name??null;
                    break;
                case self::TYPE_REGION:
                    $model = Region::findOne((int)$value);
                    return $model->name??null;
                    break;
                case self::TYPE_SUBREGION:
                    $model = Subregion::findOne((int)$value);
                    return $model->name??null;
                    break;
                case self::TYPE_CITY:
                    $model = City::findOne((int)$value);
                    return $city->name??null;
                    break;
                case self::TYPE_STREET:
                    $model = Street::findOne((int)$value);
                    return $city->name??null;
                    break;

                case self::TYPE_COLLECTIONS:
                    if (is_array($value))
                        return '<span>'.implode('</span><br/><span>', $value).'</span>';
                    else
                        return $value;
                    break;

                case self::TYPE_FILE:
                    if (is_array($value))
                    {
                        $ids = [];
                        foreach ($value as $key => $data)
                        {
                            if (is_numeric($data))
                                $ids[] = $data;
                            else if (!empty($data['id']))
                                $ids[] = $data['id'];
                        }

                        if (empty($ids))
                            return '';

                        $medias = Media::find()->where(['id_media'=>$ids])->all();

                        $output = [];
                        foreach ($medias as $key => $media) {
                            $output[] = '<a href="'.$media->getUrl().'" download="'.$media->downloadName().'"><nobr>'.$media->name.'</nobr><a>';
                        }

                        return implode('<br>', $output);
                    }
                    else
                        return '';
                    break;
                case self::TYPE_FILE_OLD:
                    $slugs = explode('/', $value);
                    return '<a href="'.$value.'">'.array_pop($slugs).'<a>';
                    break;
                case self::TYPE_IMAGE:
                    if (is_array($value) || is_numeric($value))
                    {
                        $ids = [];
                        foreach ($value as $key => $data)
                        {
                            if (is_numeric($data))
                                $ids[] = $data;
                            else if (!empty($data['id']))
                                $ids[] = $data['id'];
                        }

                        if (empty($ids))
                            return '';

                        $media = Media::find()->where(['id_media'=>$ids])->one();

                        if (!empty($media->height))
                            return '<img src="'.$media->showThumb(['w'=>200,'h'=>200]).'"/>';
                        else
                            return '<a href="'.$media->getUrl().'">'.$media->name.'</a>';
                    }
                    break;
            }
        }*/

        switch ($this->type)
        {
            case self::TYPE_DATE:
                return date('d.m.Y',$value);
                break;
            case self::TYPE_DATETIME:
                return date('d.m.Y H:i',$value);
                break;
            case self::TYPE_DISTRICT:
                $model = District::findOne((int)$value);
                return $model->name??null;
                break;
            case self::TYPE_REGION:
                $model = Region::findOne((int)$value);
                return $model->name??null;
                break;
            case self::TYPE_SUBREGION:
                $model = Subregion::findOne((int)$value);
                return $model->name??null;
                break;
            case self::TYPE_CITY:
                $model = City::findOne((int)$value);
                return $city->name??null;
                break;
            case self::TYPE_STREET:
                $model = Street::findOne((int)$value);
                return $city->name??null;
                break;
            case self::TYPE_JSON:
                if (is_array($value))
                    return $value;

                return json_decode($value,true);
                break;
            case self::TYPE_COLLECTIONS:
                if (is_array($value))
                    return '<span>'.implode('</span><br/><span>', $value).'</span>';
                else
                    return $value;
                break;
            case self::TYPE_FILE:
                if (is_array($value))
                {
                    $ids = [];
                    foreach ($value as $key => $data)
                    {
                        if (is_numeric($data))
                            $ids[] = $data;
                        else if (!empty($data['id']))
                            $ids[] = $data['id'];
                    }

                    if (empty($ids))
                        return '';

                    $medias = Media::find()->where(['id_media'=>$ids])->all();

                    $output = [];
                    foreach ($medias as $key => $media) {
                        $output[] = '<a href="'.$media->getUrl().'" download="'.$media->downloadName().'"><nobr>'.$media->name.'</nobr><a>';
                    }

                    return implode('<br>', $output);
                }
                else
                    return '';
                break;
            case self::TYPE_FILE_OLD:
                $slugs = explode('/', $value);
                return '<a href="'.$value.'">'.array_pop($slugs).'<a>';
                break;
            case self::TYPE_IMAGE:
                if (is_array($value) || is_numeric($value))
                {
                    $ids = [];
                    foreach ($value as $key => $data)
                    {
                        if (is_numeric($data))
                            $ids[] = $data;
                        else if (!empty($data['id']))
                            $ids[] = $data['id'];
                    }

                    if (empty($ids))
                        return '';

                    $media = Media::find()->where(['id_media'=>$ids])->one();

                    if (!empty($media->height))
                        return '<img src="'.$media->showThumb(['w'=>200,'h'=>200]).'"/>';
                    else
                        return '<a href="'.$media->getUrl().'">'.$media->name.'</a>';
                }
                break;
            default:

                if (is_array($value))
                    return '<span>'.implode('</span><br/><span>', $value).'</span>';

                return $value;
                break;
        }

        return '';
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
            'options' => 'Настройки',
            'keep_relation'=>'Сохранять связь',
            'template'=>'Шаблон колонки',
            'show_column_admin' => 'Show Column Admin',
            'ord' => 'Ord',
        ];
    }

    public function getCollection()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_collection']);
    }

    public function getInput()
    {
        return $this->hasOne(FormInput::class, ['id_column' => 'id_column'])->andWhere(['id_form'=>$this->collection->id_form]);
    }

    public function getInputs()
    {
        return $this->hasMany(FormInput::class, ['id_column' => 'id_column']);
    }
}
