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
            [['id_collection','id_page', 'id_group', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','make_collection','id_page'], 'integer'],
            [['name'], 'required'],
            [['message_success'], 'string'],
            [['name','url'], 'string', 'max' => 255],

            [['access_user_ids', 'access_user_group_ids', 'id_group'], 'each', 'rule' => ['integer']],
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
            'name' => 'Название',
            'id_group' => 'Группа',
            'make_collection'=>'Создать коллекцию',
            'message_success'=>'Сообщение при успешном отправлении',
            'id_page'=>'Переход на раздел при успешном отправлении',
            'url'=>'Переход на ссылку при успешном отправлении',
            'created_at' => 'Создана',
            'created_by' => 'Created By',
            'updated_at' => 'Изменено',
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
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.form',
            ],
            'multiupload' => [
                'class' => \common\components\multifile\MultiUploadBehavior::class,
                'relations'=>
                [
                    'template'=>[
                        'model'=>'Media',
                        'fk_cover' => 'id_media_template',
                        'cover' => 'template',
                    ],
                ],
                'cover'=>'template'
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
        return $this->hasMany(FormRow::class, ['id_form' => 'id_form'])->orderBy('ord ASC');
    }

    public function getService()
    {
        return $this->hasMany(Service::class, ['id_form' => 'id_form']);
    }

    public function getTemplate()
    {
        return $this->hasOne(Media::class, ['id_media' => 'id_media_template']);
    }

    public function getGroup()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_group']);
    }

    public function makeDoc($collectionRecord, $addData=null)
    {
        if (empty($this->template))
            return false;

        $media = $this->template;
        $url = $media->getUrl();

        $data = $collectionRecord->getData(true);

        $template = file_get_contents($url);
        $root = Yii::getAlias('@app');

        $template_path = $root.'/runtime/templates/template_'.$media->id_media.'_'.time().'.docx';
        $template = file_put_contents($template_path,file_get_contents($url));

        $export_path = \common\components\worddoc\WordDoc::makeDocByForm($this, $data, $template_path);

        /*header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$appeal->targer->number.' '.$appeal->created_at.'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($export_path));

        readfile($export_path);
        unlink($export_path);
        */

        return $export_path;
    }
}
