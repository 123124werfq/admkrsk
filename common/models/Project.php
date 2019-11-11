<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_project".
 *
 * @property int $id_project
 * @property int $id_media
 * @property int $id_page
 * @property int $name
 * @property int $type
 * @property int $date_begin
 * @property int $date_end
 * @property string $url
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'date_begin'], 'required'],
            [['id_media', 'id_page', 'type', 'date_begin', 'date_end', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_media', 'id_page', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['url','name'], 'string', 'max' => 255],
            [['date_begin', 'date_end'],'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_project' => 'ID',
            'id_media' => 'Обложка',
            'id_page' => 'Раздел',
            'name' => 'Название',
            'type' => 'Тип',
            'date_begin' => 'Дата начала',
            'date_end' => 'Дата конца',
            'url' => 'URL',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
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
            /*'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
            'ac' => [
                'class' => UserAccessControlBehavior::class,
                'permission' => 'backend.news',
            ],*/
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
        if (!empty($this->page))
            return $this->page->getUrl();

        return $this->url;
    }

    public function getTypeValue()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'type']);
    }

    public function getPage()
    {
        return $this->hasOne(Page::class, ['id_page' => 'id_page']);
    }

    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id_media' => 'id_media']);
    }

    public function beforeValidate()
    {
        if (!empty($this->date_begin) && !is_numeric($this->date_begin))
            $this->date_begin = strtotime($this->date_begin);

        if (!empty($this->date_end) && !is_numeric($this->date_end))
            $this->date_end = strtotime($this->date_end);

        return parent::beforeValidate();
    }
}
