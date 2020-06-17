<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\yiinput\RelationBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
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
    use AccessTrait;

    const VERBOSE_NAME = 'Форма';
    const VERBOSE_NAME_PLURAL = 'Формы';
    const TITLE_ATTRIBUTE = 'name';

    public $access_user_ids;
    public $access_user_group_ids;
    public $client_type; // для формы услуг

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
            [['id_collection','id_page', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','id_box','maxfilesize'], 'default', 'value' => null],
            [['state'], 'default', 'value' => 1],
            [['id_collection', 'id_service', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','id_page','id_box','state','is_template','maxfilesize'], 'integer'],
            [['alias'], 'unique'],
            [['name'], 'required'],
            [['captcha'], 'boolean'],
            [['message_success'], 'string'],
            [['client_type'], 'safe'],
            [['name','url','fullname','alias'], 'string', 'max' => 255],
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
            'alias' => 'Системное название формы',
            'name' => 'Название',
            'fullname' => 'Полное название',
            'id_box' => 'Группа',
            'captcha' => 'Требовать ввод капчи',
            'state' => 'Включена',
            'maxfilesize'=>'Максимальный размер всех файлов формы',
            'message_success'=>'Сообщение при успешном отправлении',
            'id_page'=>'Переход на раздел при успешном отправлении',
            'url'=>'Переход на ссылку при успешном отправлении',
            'is_template' => "Это подформа",
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
            'yiinput' => [
                'class' => RelationBehavior::class,
                'relations'=> [
                    'partitions'=>[
                        'modelname'=>'Page',
                        'jtable'=>'dbl_form_page',
                        'added'=>false,
                    ],
                ]
            ],
        ];
    }

    public function isMainForm()
    {
        return (!empty($this->collection) && ($this->id_form == $this->collection->id_form || $this->is_template==2) && empty($this->collection->id_parent_collection));
    }

    public function createInput($attributes)
    {
        $row = new FormRow;
        $row->id_form = $this->id_form;
        $row->ord = FormRow::find()->where(['id_form'=>$this->id_form])->count();

        if ($row->save())
        {
            $input = new FormInput;
            $input->attributes = $attributes;
            $input->id_form = $this->id_form;

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

    public function makeDocByData($data,$addData=[])
    {
        if (!empty($this->template))
            $media = $this->template;
        else if (!empty($this->service->template))
            $media = $this->service->template;

        $url = $media->getUrl();

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        $root = Yii::getAlias('@app');

        $template_path = $root.'/runtime/templates/template_'.$media->id_media.'_'.time().'.docx';
        file_put_contents($template_path,file_get_contents($url, false, stream_context_create($arrContextOptions)));

        $export_path = \common\components\worddoc\WordDoc::makeDocByForm($this, $data, $template_path,$addData);

        return $export_path;
    }

    public function makeDoc($collectionRecord, $addData=[])
    {
        $data = $collectionRecord->getData(true);

        return $this->makeDocByData($data,$addData);
    }

    public function renderMessage($record,$add_data=[])
    {
        $recorData = $record->getData(true);

        $value = '';

        try {
            $loader = new \Twig\Loader\ArrayLoader([
                'template' => $this->message_success,
            ]);
            $twig = new \Twig\Environment($loader);
            $value = $twig->render('template', array_merge($recorData,$add_data));

            unset($loader);
            unset($twig);
        }
        catch (Exception $e) {

        }

        return $value;
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
        return $this->hasOne(Service::class, ['id_service' => 'id_service']);
    }

    public function getInputs()
    {
        if ($this->isMainForm())
            return $this->hasMany(FormInput::class, ['id_form' => 'id_form']);
        else
            return FormInput::find()->where('id_input IN (
            SELECT id_input
                FROM form_element as fi
                INNER JOIN form_row as fr ON fr.id_row = fi.id_row
            WHERE fr.id_form = '.$this->id_form.')');
    }

    public function getTemplate()
    {
        return $this->hasOne(Media::class, ['id_media' => 'id_media_template']);
    }

    public function getBox()
    {
        return $this->hasOne(Box::class, ['id_box' => 'id_box']);
    }

    public function getPartitions()
    {
        return $this->hasMany(Page::class, ['id_page' => 'id_page'])->viaTable('dbl_form_page',['id_form'=>'id_form']);
    }
}
