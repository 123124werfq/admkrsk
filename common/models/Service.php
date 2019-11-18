<?php

namespace common\models;

use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
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

    const VERBOSE_NAME = 'Муниципальная услуга';
    const VERBOSE_NAME_PLURAL = 'Муниципальные услуги';
    const TITLE_ATTRIBUTE = 'name';

    public $id_situations = [];

    const TYPE_PEOPLE = 2;
    const TYPE_FIRM = 4;

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
            [['id_rub','client_type','name'],'required'],
            [['id_rub', 'client_type', 'old', 'online', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','refuse'], 'default', 'value' => null],
            [['id_rub', 'old', 'online', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_form'], 'integer'],
            [['keywords', 'addresses', 'result', 'client_category', 'duration', 'documents', 'price', 'appeal', 'legal_grounds', 'regulations', 'refuse','regulations_link', 'duration_order', 'availability', 'procedure_information', 'max_duration_queue'], 'string'],
            [['id_situations', 'client_type'],'safe'],
            [['reestr_number'], 'string', 'max' => 255],
            [['fullname', 'name'], 'string'],
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
            'id_form' => 'Форма приема заявления',
            'reestr_number' => 'Реестровый номер услуги',
            'fullname' => 'Полное наименование',
            'name' => 'Название',
            'keywords' => 'Корпоративные ключевые слова',
            'addresses' => 'Сведения о местах информирования о порядке предоставления услуги',
            'result' => 'Результат предоставления услуги',
            'client_type' => 'Физ./Юр. ',
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
            'online' => 'Форма предоставления',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public static function getAttributeValues($attribute,$model=null)
    {
        if ($attribute=='client_type')
            return [
                self::TYPE_PEOPLE=>'Физическое лицо',
                self::TYPE_FIRM=>'Юридическое лицо'
            ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            $result = 0;
            
            if (is_array($this->client_type))
            {
                foreach ($this->client_type as $key => $value) {
                    $result += $value;
                }
            }

            $this->client_type = $result;

            return true;
        }
        else
            return false;
    }


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

        parent::afterSave($insert, $changedAttributes);
    }

    public function getUrl()
    {
        return Url::to(['service/view', 'id' => $this->id_service]);
    }

    public function getSituations()
    {
        return $this->hasMany(ServiceSituation::class, ['id_situation' => 'id_situation'])->viaTable('servicel_situation',['id_service'=>'id_service']);
    }

    public function getRubric()
    {
        return $this->hasOne(ServiceRubric::class, ['id_rub' => 'id_rub']);
    }

    public function getTargets()
    {
        return $this->hasMany(ServiceTarget::class, ['id_service' => 'id_service']);
    }

    public function getForm()
    {
        return $this->hasOne(Form::class, ['id_form' => 'id_form']);
    }
}