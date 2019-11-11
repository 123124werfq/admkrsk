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
            [['first_name', 'last_name', 'last_name', 'middle_name', 'trusted', 'home_phone', 'living_addr', 'living_addr_fias', 'register_addr', 'register_addr_fias'], 'string'],
        ];
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

            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'trusted' => 'Запись подтверждена',

            'home_phone' => 'Домашний номер',
            'living_addr' => 'Адрес проживания',
            'living_addr_fias' => 'ФИАС для адреса проживания',
            'register_addr' => 'Адрес регистрации',
            'register_addr_fias' => 'ФИАС для адреса регстрации',

            'usr_org' => 'Организация пользователя',
            'usr_avt' => 'Аватар',
            'org_shortname' => 'Краткое наименование организации',
            'org_fullname' => 'Полное наименование организации',
            'org_type' => 'Тип организации',
            'org_ogrn' => 'ОГРН',
            'org_inn' => 'ИНН',
            'org_leg' => 'ЛЕГ',
            'org_kpp' => 'КПП',
            'org_ctts' => 'Контактаы организации',
            'org_addrs' => 'Адреса организации',

            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function actualize($client)
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
                        $this->living_addr = $address['zipCode'] . ', ' .  $address['addressStr'] . ', ' . $address['house'] . ', ' . $address['flat'];
                        $this->living_addr_fias = $address['fiasCode'];
                        break;
                    case 'PRG':
                        $this->register_addr = $address['zipCode'] . ', ' .  $address['addressStr'] . ', ' . $address['house'] . ', ' . $address['flat'];
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
