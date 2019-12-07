<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

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
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Форма';
    const VERBOSE_NAME_PLURAL = 'Формы';
    const TITLE_ATTRIBUTE = 'name';

    public $make_collection = 0;

    public $access_user_ids;
    public $access_user_group_ids;

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
            [['id_collection','id_page', 'id_service', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','id_group'], 'default', 'value' => null],
            [['id_collection', 'id_service', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','make_collection','id_page','id_group'], 'integer'],
            [['name'], 'required'],
            [['message_success'], 'string'],
            [['name','url','fullname'], 'string', 'max' => 255],
            [['access_user_ids', 'access_user_group_ids'], 'each', 'rule' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            ['access_user_group_ids', 'each', 'rule' => ['exist', 'targetClass' => UserGroup::class, 'targetAttribute' => 'id_user_group']],
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
            'id_service' => 'Услуга',
            'name' => 'Название',
            'fullname' => 'Полное название',
            'make_collection'=>'Создать коллекцию',
            'message_success'=>'Сообщение при успешном отправлении',
            'id_page'=>'Переход на раздел при успешном отправлении',
            'url'=>'Переход на ссылку при успешном отправлении',
            'id_group'=>'Группа',
            'created_at' => 'Создана',
            'created_by' => 'Кем создана',
            'updated_at' => 'Изменено',
            'updated_by' => 'Кем отредактирована',
            'deleted_at' => 'Удалена',
            'deleted_by' => 'Кем удалена',
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
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.form',
            ],
        ];
    }

    // deprecated
    public function createFromByCollection()
    {
        foreach ($this->collection->columns as $key => $column)
        {
            $row = new FormRow;
            $row->id_form = $this->id_form;
            $row->ord = $key;

            if ($row->save())
            {
                $input = new FormInput;
                $input->type = $column->type;
                $input->id_form = $this->id_form;
                $input->id_column = $column->id_column;
                $input->label = $input->name = $column->name;

                if ($column->type != CollectionColumn::TYPE_COLLECTION)
                    $input->values = $column->variables;

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
        return $this->hasOne(Service::class, ['id_service' => 'id_service']);
    }
}
