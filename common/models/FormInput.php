<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
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
            [['id_form', 'id_type', 'id_collection', 'size', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'id_column', 'deleted_by','label','id_collection_column'], 'default', 'value' => null],
            [['id_form', 'id_type', 'id_collection', 'size', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_column', 'required','type','readonly','id_collection_column'], 'integer'],
            [['name', 'type', 'fieldname'], 'required'],
            ['id_collection_column', 'required', 'when' => function($model) {
                return (!empty($model->id_collection));
            }],
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
            'id_type' => 'Поведение поля',
            'required' => 'Обязательно',
            'readonly' => 'Только для чтения',
            'id_collection' => 'Данные из списка',
            'id_collection_column'=>'Поле из списка',
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
        if ($this->type == CollectionColumn::TYPE_SERVICE)
        {
            $records = ServiceComplaintForm::find()
                            ->with('service')
                            ->where(['id_form'=>$this->id_form])
                            ->all();
            $output = [];

            foreach ($records as $key => $data)
                $output[$data->id_service] = $data->service->reestr_number.' '.$data->service->name;

            return $output;
        }
        elseif (!empty($this->typeOptions->service_attribute))
        {
            $values = Service::getAttributeValues($this->typeOptions->service_attribute,$model);
            return $values;
        }
        else if (!empty($this->typeOptions->esia))
        {
            $values = EsiaUser::getAttributeValues($this->typeOptions->esia,$model);
            return $values;
        }
        else if ($this->type == CollectionColumn::TYPE_SERVICETARGET)
        {
            $records = ServiceTarget::find()->where(['id_form'=>$this->id_form])->all();
            $output = [];

            foreach ($records as $key => $data)
                $output[$data->id_target] = $data->name;

            return $output;
        }
        else if (!empty($this->id_collection))
        {

            $collection = Collection::findOne($this->id_collection);

            if (!empty($collection))
                return $collection->getArray($this->id_collection_column??'');
            else
                return [];
        }

        $values = [];

        if (!empty($this->values))
        {
            $vars = explode(';', $this->values);

            foreach ($vars as $key => $value)
                $values[$value] = $value;
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


        if (is_string($this->values))
        {
            $data = json_decode($this->values,true);

            // какаято проблема с экранированием строки в PG
            if (is_string($data))
            {
                $data = json_decode($data,true);
            }
        }

        //var_dump($data);
        //die();

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

    public function getCollection()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_collection']);
    }

    public function getColumn()
    {
        return $this->hasOne(CollectionColumn::class, ['id_column' => 'id_column']);
    }

    public function getTypeOptions()
    {
        return $this->hasOne(FormInputType::class, ['id_type' => 'id_type']);
    }
}