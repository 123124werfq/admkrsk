<?php

namespace common\models;

use Yii;
use common\components\yiinput\RelationBehavior;

/**
 * This is the model class for table "form_element".
 *
 * @property int $id_element
 * @property int $id_form
 * @property int $type
 * @property string $content
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class FormElement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_element';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_form', 'id_row', 'type', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_form', 'type', 'id_row', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['options','content'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_element' => 'Id Element',
            'id_form' => 'Форма',
            'type' => 'Тип',
            'content' => 'Содержимое',
            'ord' => 'Ord',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getRow()
    {
        return $this->hasOne(FormRow::class, ['id_row' => 'id_row']);
    }

    public function getInput()
    {
        return $this->hasOne(FormInput::class, ['id_input' => 'id_input']);
    }

    public function getSubForm()
    {
        return $this->hasOne(Form::class, ['id_form' => 'id_form']);
    }

    public function getStyles()
    {
        $styles = [];

        if (!empty($this->options))
        {
            foreach ($this->options as $style => $value)
            {
                $styles[$style] = $style.':'.$value;

                if ($style=='width')
                    $styles[$style] .= '%';
                else
                    $styles[$style] .= 'px';
            }
        }

        return $styles;
    }

    public function behaviors()
    {
        return [
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

    public function getOptionsData()
    {
        $options = [
            'width'=>[
                'name'=>'Ширина %',
                'type'=>'number',
                'value'=>'100',
                'min'=>1,
                'max'=>100
            ],
            'margin-top'=>[
                'name'=>'Отступ сверху',
                'type'=>'number',
                'min'=>0,
                'value'=>0,
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

    public function getVisibleInputs()
    {
        return $this->hasMany(FormVisibleInput::class, ['id_element' => 'id_element']);
    }
}