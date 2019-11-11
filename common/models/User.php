<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property array $roles
 * @property string $statusName
 * @property string $pageTitle
 * @property string $breadcrumbsLabel
 *
 * @property EsiaUser $esiainfo
 * @property AdUser $adinfo
 * @property Media $media
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    // CdtDblGfh - ESIA pass
    // 240501 - ESIA id

    public $roles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'multiupload' => [
                'class' => \common\components\multifile\MultiUploadBehavior::class,
                'relations'=>
                [
                    'media'=>[
                        'model'=>'Media',
                        'fk_cover' => 'id_media',
                        'cover' => 'media',
                    ],
                ],
                'cover'=>'media'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username','phone','fullname'], 'string', 'max' => 255],
            ['email', 'email'],
            [['description'],'safe'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['roles', 'filter', 'filter' => function ($value) {
                return is_array($value) ? array_unique($value) : $value;
            }],
            ['roles', 'each', 'rule' => ['in', 'range' => array_keys(self::getRoleNames())]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '#',
            'id_media'=>'Фотография',
            'status' => 'Статус',
            'username' => 'Пользователь',
            'email' => 'Email',
            'id_esia_user' => 'ID ЕСИА',
            'id_ad_user' => 'ID Active Directory',
            'fullname' => 'Имя',
            'phone' => 'Телефон',
            'description' => 'Описание',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
            'roles' => 'Роли',
        ];
    }

    /**
     * @return string
     */
    public function getBreadcrumbsLabel()
    {
        return 'Пользователи';
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->setAssignments($this->roles);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     *
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     *
     * @throws \yii\base\Exception
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new email verification token
     *
     * @throws \yii\base\Exception
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Назначение прав
     */
    public function setAssignments()
    {
        $auth = Yii::$app->authManager;

        $oldRoles = Yii::$app->authManager->getRolesByUser($this->id);
        foreach ($oldRoles as $oldRole) {
            $auth->revoke($oldRole, $this->id);
        }

        if (is_array($this->roles)) {
            foreach ($this->roles as $role) {
                if (($role = $auth->getRole($role)) !== null) {
                    $auth->assign($role, $this->id);
                }
            }
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEsiainfo()
    {
        return $this->hasOne(EsiaUser::class, ['id_esia_user' => 'id_esia_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdinfo()
    {
        return $this->hasOne(AdUser::class, ['id_ad_user' => 'id_ad_user']);
    }

    public function getUsername()
    {
        if(!empty($this->id_esia_user))
        {
            $ei = $this->getEsiainfo()->one();
            return $ei->fullname;
        }

        if(!empty($this->id_ad_user))
        {
            $ad = $this->getAdinfo()->one();
            return $ad->displayname;
        }

        return $this->username;

    }

    public function getMedia()
    {
        return $this->hasOne(Media::class, ['id_media' => 'id_media']);
    }

    /**
     * Возвращает массив ролей
     *
     * @return array
     */
    public static function getRoleNames()
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }

    /**
     * Возвращает массив ролей пользователя
     *
     * @param string $role
     * @return string
     */
    public function getRoleName($role)
    {
        $roles = self::getRoleNames();

        if ($roles[$role]) {
            return $roles[$role];
        }

        return null;
    }

    /**
     * Возвращает массив статусов
     *
     * @return array
     */
    public static function getStatusNames()
    {
        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_INACTIVE => 'Не активный',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    /**
     * Возвращает название статуса
     *
     * @return string
     */
    public function getStatusName()
    {
        $statuses = self::getStatusNames();

        if ($statuses[$this->status]) {
            return $statuses[$this->status];
        }

        return null;
    }

    /**
     * Проверка доступа
     * @param array|string $permissions
     * @return bool
     */
    public function can($permissions)
    {
        if (is_string($permissions)) {
            return Yii::$app->authManager->checkAccess($this->id, $permissions);
        }

        if (is_array($permissions)) {
            foreach ($permissions as $key => $permission) {
                if (is_array($permission)) {
                    if (Yii::$app->authManager->checkAccess($this->id, $key, $permission)) {
                        return true;
                    }
                } else {
                    if (Yii::$app->authManager->checkAccess($this->id, $permission)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }


    static public function findByOid($oid)
    {
        $user = User::find()->where(['email' => $oid.'@esia.ru'])->one();
        return $user;
    }
}
