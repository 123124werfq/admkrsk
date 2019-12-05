<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\components\yiinput\RelationBehavior;
use common\modules\log\behaviors\LogBehavior;

/**
 * This is the model class for table "form_input".
 *
 * @property int $id_input
 * @property int $id_form
 * @property int $id_type
 * @property int $id_collection
 * @property string $name
 * @property string $fieldname
 * @property int $visibleInput
 * @property string $visibleInputValue
 * @property string $values
 * @property int $size
 * @property string $options
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class FormInput extends \yii\db\ActiveRecord
{
    public $alias, $visibleValues;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_input';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_form', 'id_type', 'id_collection', 'size', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','label'], 'default', 'value' => null],
            [['id_form', 'id_type', 'id_collection', 'size', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','required','type'], 'integer'],
            [['name', 'type'], 'required'],
            [['hint','label'], 'string'],
            [['options','values'],'safe'],
            [['name', 'fieldname','alias'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_input' => 'Id Input',
            'id_form' => 'Форма',
            'type' => 'Тип поля',
            'id_type' => 'Пресет поля',
            'required' => 'Обязательно',
            'id_collection' => 'Коллекция',
            'label' => 'Подпись',
            'name' => 'Название',
            'hint' => 'Пояснение',
            'fieldname' => 'Псевдоним переменной',
            'values' => 'Значения',
            'size' => 'Размер',
            'options' => 'Опции',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
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
            'yiinput' => [
                'class' => RelationBehavior::class,
                'relations'=> [
                    'visibleInputs'=>[
                        'modelname'=> 'FormVisibleInput',
                        'added'=>true,
                    ],
                ]
            ]
        ];
    }

    public function beforeValidate()
    {
        if ($this->type==CollectionColumn::TYPE_JSON)
            $this->values = json_encode($this->values);

        return parent::beforeValidate();
    }

    public function getArrayValues($model=null)
    {
        if (!empty($this->type->service_attribute))
        {
            $values = Service::getAttributeValues($this->type->service_attribute,$model);
            return $values;
        }

        $values = [];

        if (!empty($this->values))
        {
            $vars = explode(';', $this->values);

            foreach ($vars as $key => $value) {
                $values[$value] = $value;
            }
        }

        return $values;
    }

    public function getTableOptions()
    {
        $options = [
            'name'=>[
                'name'=>'Название',
                'type'=>'input',
                'value'=>'',
            ],
            'width'=>[
                'name'=>'Ширина %',
                'type'=>'number',
                'value'=>'100',
                'min'=>1,
                'max'=>100
            ],
            'type'=>[
                'name'=>'Тип ввода',
                'type'=>'dropdown',
                'value'=>'',
                'values'=>[
                    'text'=>"Текст",
                    'email'=>"Емейл",
                    'number'=>"Число",
                    'url'=>"Ссылка",
                    'datetime'=>"Дата+Время",
                    'date'=>"Дата",
                ],
            ],
        ];

        $data = json_decode($this->values,true);

        if (empty($data))
            return [$options];

        $output = [];

        foreach ($data as $key => $row)
        {
            $line = $options;

            foreach ($line as $key => $value)
            {
                if (!empty($row[$key]))
                    $line[$key]['value'] = $row[$key];
            }

            $output[] = $line;
        }

        return $output;
    }


    public function getElement()
    {
        return $this->hasOne(FormElement::class, ['id_input' => 'id_input']);
    }

    public function getColumn()
    {
        return $this->hasOne(CollectionColumn::class, ['id_column' => 'id_column']);
    }

    public function getTypeOptions()
    {
        return $this->hasOne(FormInputType::class, ['id_type' => 'id_type']);
    }

    public function getVisibleInputs()
    {
        return $this->hasMany(FormVisibleInput::class, ['id_input' => 'id_input']);
    }

}
