<?php

namespace common\models;

use Yii;
use common\modules\log\behaviors\LogBehavior;
use yii\behaviors\BlameableBehavior;
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
          'ba' => BlameableBehavior::class,
          'log' => LogBehavior::class,
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
