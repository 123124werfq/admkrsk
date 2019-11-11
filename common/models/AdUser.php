<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_ad_user".
 *
 * @property int $id_ad_user
 * @property int $id_user
 * @property string $name
 * @property string $guid
 * @property string $sid
 * @property string $office_name
 * @property string $group_id
 * @property string $sam_acc_name
 * @property string $sam_acc_type
 * @property string $sn
 * @property string $street_address
 * @property string $telephone_number
 * @property string $title
 * @property string $principal
 * @property string $thumbnail
 * @property string $homepage
 * @property string $when_created
 * @property string $when_changed
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $deleted_by
 */
class AdUser extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_ad_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['thumbnail', 'public', 'city', 'company', 'department', 'description', 'displayname', 'email', 'givenname', 'fax', 'otherphones', 'phone', 'office'], 'string'],
            [['name', 'guid', 'sid', 'office_name', 'group_id', 'sam_acc_name', 'sam_acc_type', 'sn', 'street_address', 'telephone_number', 'title', 'principal', 'homepage', 'when_created', 'when_changed', 'sn'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_ad_user' => 'Id Ad User',
            'id_user' => 'Id User',
            'name' => 'ФИО',
            'guid' => 'Guid',
            'sid' => 'SID',
            'office_name' => 'Наименование офиса',
            'group_id' => 'Group ID',
            'sam_acc_name' => 'Sam Acc Name',
            'sam_acc_type' => 'Sam Acc Type',
            'sn' => 'Sn',
            'street_address' => 'Street Address',
            'telephone_number' => 'Telephone Number',
            'title' => 'Title',
            'principal' => 'Principal',
            'thumbnail' => 'Thumbnail',
            'homepage' => 'Homepage',

            'public' => 'Доступно для просмотра',
            'city' => 'Город',
            'company' => 'Организация',
            'department' => 'Департамент',
            'description' => 'Описание',
            'displayname' => 'Отображаемое имя',
            'email' => 'email',
            'givenname' => 'Имя',
            'fax' => 'Факс',
            'otherphones' => 'Дополнительные телефоны',
            'phone' => 'Телефон',
            'office' => 'Офис',

            'when_created' => 'When Created',
            'when_changed' => 'When Changed',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }


    private function createInfo($un)
    {
        $ldapObject = \Yii::$app->ad->search()->findBy('sAMAccountname', $un);

        $this->sn = $un;
        $this->name = $ldapObject['name'][0];
        $this->city = $ldapObject['city'][0];
        $this->company = $ldapObject['company'][0];
        $this->department = $ldapObject['department'][0];
        $this->description = $ldapObject['description'][0];
        $this->title = $ldapObject['title'][0];
        $this->displayname = $ldapObject['displayname'][0];
        $this->email = $ldapObject['email'][0];
        $this->fax = $ldapObject['fax'][0];
        $this->office = $ldapObject['office'][0];
        $this->phone = $ldapObject['office'][0];
        $this->sid = implode(unpack('C*', $ldapObject['objectsid'][0]));

        $this->updated_at = time();

        if(!$this->save(false))
            return false;

        return $this->id_ad_user;
    }

    static public function adlogin($login, $password)
    {
        $host = '10.24.0.7'; // вынести в настройки
        $mydap = ldap_connect($host);

        if(!$mydap)
            return false;

        ldap_set_option($mydap,LDAP_OPT_PROTOCOL_VERSION,3);
        $bindRes = @ldap_bind($mydap,$login,$password);

        if(!$bindRes)
            return false;

        // если логин прошел, то ищем пользака в базе, если нет - создаём
        $login = str_replace('@admkrsk.ru', '', $login);
        $localuser = User::findByUsername($login );

        $aduser = AdUser::find()->where(['sn' => $login])->one();
        if($aduser)
            $id_ad_user = $aduser->createInfo($login);

        if($localuser)
            return Yii::$app->user->login($localuser,3600 * 24 * 30);

        if(!$aduser) {
            $aduser = new AdUser();
            $id_ad_user = $aduser->createInfo($login);
        }

        $user = new User();
        $user->email = $login.'@admkrsk.ru';
        $user->username = $aduser->sn;
        $user->setPassword($aduser->sid);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        $user->id_ad_user = $id_ad_user;

        //$aduser->id_user = $user->id;

        if($user->save())
            return Yii::$app->user->login($user,3600 * 24 * 30);
        else
            return false;

    }

}
