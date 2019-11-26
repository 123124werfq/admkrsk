<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use common\modules\log\behaviors\LogBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Url;

/**
 * This is the model class for table "service_situation".
 *
 * @property int $id_situation
 * @property int $id_media
 * @property int $id_parent
 * @property string $name
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class ServiceSituation extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Жизненная ситуация';
    const VERBOSE_NAME_PLURAL = 'Жизненные ситуации';
    const TITLE_ATTRIBUTE = 'name';

    public $access_user_ids;
    public $access_user_group_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_situation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'],'required'],
            [['id_media', 'id_parent', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_media', 'id_parent', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 255],

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
          'id_situation' => 'Id Situation',
          'id_media' => 'Иконка',
          'id_parent' => 'Родительский элемент',
          'name' => 'Название',
          'ord' => 'Ord',
          'created_at' => 'Created At',
          'created_by' => 'Created By',
          'updated_at' => 'Updated At',
          'updated_by' => 'Updated By',
          'deleted_at' => 'Deleted At',
          'deleted_by' => 'Deleted By',
        ];
    }

    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.serviceSituation',
            ],
            'multiupload' => [
                'class' => \common\components\multifile\MultiUploadBehavior::className(),
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

    public function getUrl()
    {
        return Url::to(['service/reestr', 'id_situation' => $this->id_situation]);
    }

    public function getChilds()
    {
        return $this->hasMany(ServiceSituation::className(), ['id_parent' => 'id_situation']);
    }

    public function getParent()
    {
        return $this->hasMany(ServiceSituation::className(), ['id_situation' => 'id_parent']);
    }

    public function getServices()
    {
        return $this->hasMany(Service::className(), ['id_situation' => 'id_situation']);
    }

    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id_media' => 'id_media']);
    }
}
