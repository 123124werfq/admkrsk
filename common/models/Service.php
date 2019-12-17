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
use yii\helpers\Url;

/**
 * This is the model class for table "service_service".
 *
 * @property int $id_service
 * @property int $id_rub
 * @property string $reestr_number
 * @property string $fullname
 * @property string $name
 * @property string $keywords
 * @property string $addresses
 * @property string $result
 * @property int $client_type
 * @property string $client_category
 * @property string $duration
 * @property string $documents
 * @property string $price
 * @property string $appeal
 * @property string $legal_grounds
 * @property string $regulations
 * @property string $regulations_link
 * @property string $duration_order
 * @property string $availability
 * @property string $procedure_information
 * @property string $max_duration_queue
 * @property int $old
 * @property int $online
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Service extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Муниципальная услуга';
    const VERBOSE_NAME_PLURAL = 'Муниципальные услуги';
    const TITLE_ATTRIBUTE = 'name';

    public $id_situations = [];
    public $id_firms = [];

    const TYPE_PEOPLE = 2;
    const TYPE_FIRM = 4;

    public $access_user_ids;
    //public $id_target;
    public $access_user_group_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_rub','client_type','name'], 'required'],
            [['id_rub', 'online', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','refuse'], 'default', 'value' => null],
            [['old'], 'default', 'value' => 0],
            [['id_rub', 'old', 'online', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['keywords', 'addresses', 'result', 'client_category', 'duration', 'documents', 'price', 'appeal', 'legal_grounds', 'regulations', 'refuse','regulations_link', 'duration_order', 'availability', 'procedure_information', 'max_duration_queue'], 'string'],
            [['id_situations', 'client_type', 'id_firms'], 'safe'],
            [['reestr_number','ext_url'], 'string', 'max' => 255],
            [['fullname', 'name', 'type'], 'string'],
            [['test'],'safe'],
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
            'id_service' => 'ID',
            'id_rub' => 'Рубрика',
            //'id_target'=> 'Цель',
            'reestr_number' => 'Реестровый номер услуги',
            'fullname' => 'Полное наименование',
            'name' => 'Название',
            'ext_url' => 'Внешняя сслыка услуги',
            'type'=> 'Орган или Учреждение',
            'keywords' => 'Корпоративные ключевые слова',
            'addresses' => 'Сведения о местах информирования о порядке предоставления услуги',
            'result' => 'Результат предоставления услуги',
            'client_type' => 'Физ./Юр.',
            'client_category' => 'Категория заявителя',
            'duration' => 'Срок предоставления услуги',
            'refuse' => 'Перечень оснований для отказа в предоставлении услуги',
            'documents' => 'Перечень документов, необходимых для предоставления услуги',
            'price' => 'Сведения о платности (безвозмездности) предоставления услуги',
            'appeal' => 'Сведения о порядке досудебного (внесудебного) обжалования',
            'legal_grounds' => 'Правовые основания для предоставления услуги',
            'regulations' => 'Административный (типовой) регламент предоставления услуги (Регламент)',
            'regulations_link' => 'Административный регламент - Ссылка',
            'duration_order' => 'Срок и порядок регистрации запроса заявителя о предоставлении муниципальной услуги',
            'availability' => 'Показатели доступности и качества муниципальной услуги',
            'procedure_information' => 'Информация о внутриведомственных и межведомственных административных процедурах, подлежащих выполнению органом муниципального образования при предоставлении муниципальной услуги',
            'max_duration_queue' => 'Максимальный срок ожидания в очереди при подаче запроса о предоставлении муниципальной услуги, если заявитель захочет лично подать запрос о предоставлении муниципальной услуги',
            'old' => 'Услуга устарела',
            'id_situations'=>'Жизенные ситуации',
            'id_firms'=>'Обслуживающие организации',
            'online' => 'Форма предоставления',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Обновлено',
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
                'permission' => 'backend.service',
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

    public static function getAttributeValues($attribute,$model=null)
    {
        if ($attribute=='client_type')
        {
            return [
                'Физическое лицо' => 'Физическое лицо',
                'Юридическое лицо' => 'Юридическое лицо'
            ];
        }
        else if ($attribute=='type')
            return [
                'услуга органа'=>'услуга органа',
                'настройка для ДО'=>'настройка для ДО',
                'услуга учреждения'=>'услуга учреждения',
            ];

        /*if ($attribute=='id_target')
        {
            $output = [];

            foreach ($this->targets as $key => $target)
                $output[$target->id_target] = $target->name;

            return $output;
        }*/

        return [];
    }

    /*public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {

            return true;
        }
        else
            return false;
    }*/

    public function afterSave($insert, $changedAttributes)
    {
        if (isset($_POST['Service']['id_situations']))
        {
            Yii::$app->db->createCommand()->delete('servicel_situation',['id_service'=>$this->id_service])->execute();

            if (!empty($this->id_situations))
            {
                $situations = ServiceSituation::find()->select('id_situation')->where(['id_situation'=>$this->id_situations])->all();

                foreach ($situations as $key => $data)
                    $this->link('situations',$data);
            }
        }

        if (isset($_POST['Service']['id_firms']))
        {
            Yii::$app->db->createCommand()->delete('servicel_collection_firm',['id_service'=>$this->id_service])->execute();

            if (!empty($this->id_firms))
            {
                $insert = CollectionRecord::find()->select('id_record')->where(['id_record'=>$this->id_firms])->all();

                foreach ($insert as $key => $data)
                    $this->link('firms',$data);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function isAppealable()
    {
        $count = ServiceAppealForm::find()->where(['id_service'=>$this->id_service])->count();

        return ($count>0);
    }

    public function getUrl()
    {
        return Url::to(['service/view', 'id' => $this->id_service]);
    }

    public function getFirms()
    {
        return $this->hasMany(CollectionRecord::class, ['id_record' => 'id_record'])->viaTable('servicel_collection_firm',['id_service'=>'id_service']);
    }

    public function getSituations()
    {
        return $this->hasMany(ServiceSituation::class, ['id_situation' => 'id_situation'])->viaTable('servicel_situation',['id_service'=>'id_service']);
    }

    public function getTemplate()
    {
        return $this->hasOne(Media::class, ['id_media' => 'id_media_template']);
    }

    public function getRubric()
    {
        return $this->hasOne(ServiceRubric::class, ['id_rub' => 'id_rub']);
    }

    public function getForms()
    {
        return $this->hasMany(Form::class, ['id_service' => 'id_service']);
    }

    static public function generateGUID(){
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');

            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}