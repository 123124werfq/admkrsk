<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_esia_user".
 *
 * @property int $id_esia_user
 * @property int $id_user
 * @property int $is_org
 * @property string $fullname
 * @property string $birthdate
 * @property string $gender
 * @property string $snils
 * @property string $inn
 * @property string $id_doc
 * @property string $birthplace
 * @property string $medical_doc
 * @property string $residence_doc
 * @property string $email
 * @property string $mobile
 * @property string $contacts
 * @property string $usr_org
 * @property string $usr_avt
 * @property string $org_shortname
 * @property string $org_fullname
 * @property string $org_type
 * @property string $org_ogrn
 * @property string $org_inn
 * @property string $org_leg
 * @property string $org_kpp
 * @property string $org_ctts
 * @property string $org_addrs
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $deleted_by
 */
class EsiaUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_esia_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'is_org', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'is_org', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['fullname', 'birthdate', 'gender', 'snils', 'inn', 'id_doc', 'birthplace', 'medical_doc', 'residence_doc', 'email', 'mobile', 'contacts', 'usr_org', 'usr_avt', 'org_shortname', 'org_fullname', 'org_type', 'org_ogrn', 'org_inn', 'org_leg', 'org_kpp', 'org_ctts', 'org_addrs'], 'string', 'max' => 255],
//            [['first_name', 'last_name', 'last_name', 'middle_name', 'trusted', 'home_phone', 'living_addr_object', 'living_addr', 'living_addr_fias', 'firmaddrfias',  'register_addr', 'register_addr_fias'], 'string'],
            [['first_name', 'last_name', 'last_name', 'middle_name', 'trusted', 'home_phone', 'living_addr', 'living_addr_fias',  'register_addr', 'register_addr_fias'], 'string'],
            [['passport_serie', 'passport_number', 'passport_issuer', 'passport_issuer_id', 'passport_comments', 'userdoc_raw', 'mediacal_raw', 'residence_raw'], 'string'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id_esia_user' => 'id_esia_user']);
    }


    public function getLiving_addr_object()
    {
        $model =  House::find()->where(['houseguid'=>$this->living_addr_fias])->one();

        if (!empty($model))
            return $model->getArrayData();
        else
            return false;
    }

    public function getFirmaddrfias()
    {
        $model =  House::find()->where(['houseguid'=>$this->user->currentFirm->main_addr_fias_alt])->one();

        if (!empty($model))
            return $model->getArrayData();
        else
            return false;
    }

    public function getFirmkpp()
    {
        if(!$this->is_org)
            return false;
        return (string)$this->user->currentFirm->kpp;
    }

    public function getFirminn()
    {
        if(!$this->is_org)
            return false;
        return (string)$this->user->currentFirm->inn;
    }   
    
    public function getFirmogrn()
    {
        if(!$this->is_org)
            return false;
        return (string)$this->user->currentFirm->ogrn;
    }  

    public function getFirmname()
    {
        if(!$this->is_org)
            return false;
        return (string)$this->user->currentFirm->fullname;
    } 

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_esia_user' => 'Id Esia User',
            'id_user' => 'Id User',
            'is_org' => 'Организация?',
            'fullname' => 'Имя Фамилия',
            'birthdate' => 'Дата рождения',
            'gender' => 'Пол',
            'snils' => 'Номер СНИЛС',
            'inn' => 'ИНН',
            'id_doc' => 'Ссылка на документ, удостоверяющий личность',
            'birthplace' => 'Место рождения',
            'medical_doc' => 'Список медицинских документов',
            'residence_doc' => 'Список документов, подтверждающих право проживания',
            'email' => 'Email',
            'mobile' => 'Мобильный номер',
            'contacts' => 'Contacts',

            'passport_serie' => 'Серия паспорта',
            'passport_number' => 'Номер паспорта',
            'passport_date' => 'Дата выдачи паспорта',
            'passport_issuer' => 'Кем выдан паспорт',
            'passport_issuer_id' => 'Кем выдан паспорт (код подразделения)',
            'passport_comments' => 'Дополнительная информация о паспорте',

            'userdoc_raw' => 'Идентификационные документы',
            'mediacal_raw' => 'Медицинские документы',
            'residence_raw' => 'Документы, подтверждающие право проживания',

            'usertype' => 'Физ.лицо / Юр.Лицо',

            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'trusted' => 'Запись подтверждена',

            'home_phone' => 'Домашний номер',
            'living_addr' => 'Адрес проживания',
            'living_addr_fias' => 'ФИАС для адреса проживания',
            'living_addr_object' => 'Адресный объект адреса проживания',
            'firmaddrfias' => 'Адресный объект фактический адрес юр.лица',

            'register_addr' => 'Адрес регистрации',
            'register_addr_fias' => 'ФИАС для адреса регстрации',

            'usr_org' => 'Организация пользователя',
            'usr_avt' => 'Аватар',
            'org_shortname' => 'Краткое наименование организации',
            'org_fullname' => 'Полное наименование организации',
            'org_type' => 'Тип организации',
            'org_ogrn' => 'ОГРН',
            'org_inn' => 'ИНН орг',
            'org_leg' => 'ЛЕГ',
            'org_kpp' => 'КПП',
            'org_ctts' => 'Контактаы организации',
            'org_addrs' => 'Адреса организации',

            'firmkpp' => 'КПП организации (ЕСИА)',
            'firminn' => 'ИНН организации (ЕСИА)',
            'firmogrn' => 'ОГРН организации (ЕСИА)',
            'firmname' => 'Наименование организации (ЕСИА)',

            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getUsertype()
    {
        if ($this->is_org)
            return 'Юридическое лицо';

        return 'Физическое лицо';
    }

    public static function getAttributeValues($attribute,$model=null)
    {
        if ($attribute=='usertype')
        {
            return [
                'Физическое лицо' => 'Физическое лицо',
                'Юридическое лицо' => 'Юридическое лицо'
            ];
        }

        return [];
    }

    public function actualize($esia)
    {
        $personInfo = $esia->getPersonInfo();

        $this->fullname = $personInfo['lastName'].' '.$personInfo['firstName'].' '.$personInfo['middleName'];
        $this->first_name = $personInfo['firstName'];
        $this->middle_name = $personInfo['middleName']??null;
        $this->last_name = $personInfo['lastName']??null;
        $this->birthdate = $personInfo['birthDate']??null;
        $this->birthplace = $personInfo['birthPlace']??null;
        $this->trusted = (string)($personInfo['trusted']??null);
        $this->snils = $personInfo['snils']??null;
        $this->inn = $personInfo['inn']??null;

        $addressInfo = $esia->getAddressInfo();
        if(isset($addressInfo[0]))
        {
            $addrString = $addressInfo[0]['addressStr']??'';
            $flat = $addressInfo[0]['flat']??'';
            $house = $addressInfo[0]['house']??'';
            $zipode = $addressInfo[0]['zipCode']??'';

            $this->register_addr = $zipode . ', ' . $addrString . ', ' . $house . ', ' . $flat;
            $this->register_addr_fias = $addressInfo[0]['fiasCode']??null;
        }

        $contactInfo = $esia->getContactInfo();
        foreach ($contactInfo as $cinfo)
        {
            switch ($cinfo['type']){
                case 'EML':
                        $this->email = $cinfo['value'];
                        break;
                case 'MBT':
                        $this->mobile = $cinfo['value'];
                        break;
            }
        }

        $documentInfo = $esia->getDocInfo();
        foreach ($documentInfo as $dinfo)
        {
            switch ($dinfo['type']){
                case 'RF_PASSPORT':
                    $this->passport_serie = $dinfo['series'];
                    $this->passport_number = $dinfo['number'];
                    $this->passport_date = $dinfo['issueDate'];
                    $this->passport_issuer = $dinfo['issuedBy'];
                    $this->passport_issuer_id = $dinfo['issueId'];
                    break;
            }
        }


        if($this->save())
            return true;
        else {
            var_dump($this->errors);
            die();
        }

        return false;

    }

    public function actualize_old($client)
    {
        $attributes = $client->getUserAttributes();
        $oid = $attributes['oid'];
        $personInfo = $client->api($oid, "GET");

        $this->fullname = $personInfo['firstName'].' '.$personInfo['lastName'];
        $this->first_name = $personInfo['firstName'];
        $this->middle_name = $personInfo['middleName']??null;
        $this->last_name = $personInfo['lastName']??null;
        $this->birthdate = $personInfo['birthDate']??null;
        $this->birthplace = $personInfo['birthPlace']??null;
        $this->trusted = (string)($personInfo['trusted']??null);
        $this->snils = $personInfo['snils']??null;
        $this->inn = $personInfo['inn']??null;

        $contactsInfo = $client->api($oid."/ctts", "GET"); // инфа по контактам пользака
        if(isset($contactsInfo['elements']))
            foreach ($contactsInfo['elements'] as $contactURL)
            {
                $tmp = explode('/ctts/', $contactURL);
                $contact = $client->api($oid."/ctts/" . $tmp[1], "GET");

                switch ($contact['type']){
                    case 'MBT': $this->mobile = $contact['value']; break;
                    case 'PHN': $this->home_phone = $contact['value']; break;
                    case 'EML': $this->email = $contact['value']; break;
                }
            }

        $addressInfo = $client->api($oid."/addrs", "GET"); // инфа по адресам пользака
        if(isset($addressInfo['elements']))
            foreach ($addressInfo['elements'] as $addressURL)
            {
                $tmp = explode('/addrs/', $addressURL);
                $address = $client->api($oid."/addrs/" . $tmp[1], "GET");

                switch ($address['type']){
                    case 'PLV':
                        $addr_components = [];
                        if(isset($address['zipCode'])) $addr_components[] = $address['zipCode'];
                        if(isset($address['addressStr'])) $addr_components[] = $address['addressStr'];
                        if(isset($address['house'])) $addr_components[] = $address['house'];
                        if(isset($address['flat'])) $addr_components[] = $address['flat'];

                        $this->living_addr = implode(', ', $addr_components);

                        if(isset($address['fiasCode']))
                            $this->living_addr_fias = $address['fiasCode'];
                        break;
                    case 'PRG':
                        $addr_components = [];
                        if(isset($address['zipCode'])) $addr_components[] = $address['zipCode'];
                        if(isset($address['addressStr'])) $addr_components[] = $address['addressStr'];
                        if(isset($address['house'])) $addr_components[] = $address['house'];
                        if(isset($address['flat'])) $addr_components[] = $address['flat'];

                        $this->register_addr = $address['zipCode'] . ', ' .  $address['addressStr'] . ', ' . $address['house'] . ', ' . $address['flat'];
                        if(isset($address['fiasCode']))
                            $this->register_addr_fias = $address['fiasCode'];
                        break;
                }

            }

        if($this->save())
            return true;
        else {
            var_dump($this->errors);
            die();
        }

        return false;
    }
}
