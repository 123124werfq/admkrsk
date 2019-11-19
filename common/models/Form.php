<?php

namespace common\models;

use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "form_form".
 *
 * @property int $id_form
 * @property int $id_collection
 * @property string $name
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Form extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Форма';
    const VERBOSE_NAME_PLURAL = 'Формы';
    const TITLE_ATTRIBUTE = 'name';

    public $make_collection = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','make_collection'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_form' => 'ID',
            'id_collection' => 'Коллекция',
            'name' => 'Название',
            'make_collection'=>'Создать коллекцию',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function createFromByCollection()
    {
        foreach ($this->collection->columns as $key => $column)
        {
            $row = new FormRow;
            $row->id_form = $this->id_form;
            $row->ord = $key;

            if ($row->save())
            {
                $where = ['type'=>$column->type];

                if ($column->type == CollectionColumn::TYPE_COLLECTION)
                    $where['id_collection'] = $column->variables;

                $type = FormInputType::find()->where($where)->andWhere('esia IS NULL AND service_attribute IS NULL')->one();

                if (empty($type))
                {
                    $type = new FormInputType;
                    foreach ($where as $tkey => $value)
                        $type->$tkey = $value;

                    $type->name =  $column::getTypeLabel($column->type);
                    $type->save();
                }

                $input = new FormInput;
                $input->id_type = $type->id_type;
                $input->id_column = $column->id_column;
                $input->label = $input->name = $column->name;

                if ($column->type != CollectionColumn::TYPE_COLLECTION)
                    $input->values = $columns->variables;

                if ($input->save())
                {
                    $element = new FormElement;
                    $element->id_row = $row->id_row;
                    $element->id_input = $input->id_input;
                    $element->ord = 0;
                    $element->save();
                }
            }
        }
    }

    public function getCollection()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_collection']);
    }

    public function getRows()
    {
        return $this->hasMany(FormRow::class, ['id_form' => 'id_form']);
    }

    public function getService()
    {
        return $this->hasMany(Service::class, ['id_form' => 'id_form']);
    }
}
