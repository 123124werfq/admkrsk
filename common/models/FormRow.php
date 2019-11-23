<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "form_row".
 *
 * @property int $id_row
 * @property int $id_form
 * @property string $ord
 * @property string $content
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class FormRow extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_row';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_form', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_form', 'created_at', 'ord', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['ord','id_form'], 'required'],
            [['content'], 'string'],
            [['options'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_row' => 'ID',
            'id_form' => 'Форма',
            'ord' => 'Ord',
            'options' => 'Настройки',
            'content' => 'Содержимое',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /*public function getInputs()
    {
        return $this->hasMany(FormElement::class, ['id_row' => 'id_row']);
    }*/

    public function getElements()
    {
        return $this->hasMany(FormElement::class, ['id_row' => 'id_row'])->orderBy('ord ASC');
    }

    public function getForm()
    {
        return $this->hasOne(Form::class, ['id_form' => 'id_form']);
    }

    public function getOptionsData()
    {
        $options = [
            'width'=>[
                'name'=>'Положение элементов',
                'type'=>'text-align',
                'value'=>'left',
                'values'=>[
                    'left'=>'Влево',
                    'right'=>'Вправо',
                    'center'=>'По центру',
                ]
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
}
