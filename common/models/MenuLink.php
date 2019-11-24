<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_menu_link".
 *
 * @property int $id_link
 * @property int $id_parent
 * @property int $id_menu
 * @property int $id_media
 * @property int $id_page
 * @property string $label
 * @property string $url
 * @property string $content
 * @property int $state
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class MenuLink extends \yii\db\ActiveRecord
{
    public $templates = [
        'menu/situations' => 'Муниципальные услуги',
        'menu/directions' => 'Направления деятельности',
        'menu/areas' => 'Районы города',
        'news/announcement' => 'Анонсы',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_menu_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label'], 'required'],
            [['id_parent', 'id_menu', 'id_media', 'id_page', 'state', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_parent', 'id_menu', 'id_media', 'id_page', 'state', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_menu_content'], 'integer'],
            [['content','template'], 'string'],
            [['label', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_link' => 'ID',
            'id_parent' => 'Родитель',
            'id_menu' => 'Меню',
            'id_media' => 'Изображение',
            'id_page' => 'Раздел',
            'id_menu_content'=>'Подменю',
            'label' => 'Название',
            'url' => 'URL',
            'content' => 'Содержание',
            'state' => 'Статус',
            'ord' => 'Ord',
            'template' => 'Шаблон',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getUrl($absolute=false)
    {
        if (!empty($this->url))
        {
            if ($absolute && strpos($this->url, '//')===false)
                return Yii::$app->params['frontendUrl'].$this->url;

            return $this->url;
        }

        if (!empty($this->id_page))
            return $this->page->getUrl($absolute);
    }

    public function getMenu()
    {
        return $this->hasOne(Menu::class, ['id_menu' => 'id_menu']);
    }

    public function getSubMenu()
    {
        return $this->hasOne(Menu::class, ['id_menu' => 'id_menu_content']);
    }

    public function getPage()
    {
        return $this->hasOne(Page::class, ['id_page' => 'id_page']);
    }

    public function getNews()
    {
        return $this->hasMany(News::class, ['id_page' => 'id_page'])->orderBy('date_publish DESC');
    }

    public function getMedia()
    {
        return $this->hasOne(Media::className(), ['id_media' => 'id_media']);
    }

    public function getChilds()
    {
        return $this->hasMany(MenuLink::className(), ['id_parent' => 'id_link']);
    }

    public function getParent()
    {
        return $this->hasMany(MenuLink::className(), ['id_link' => 'id_parent']);
    }

    public function behaviors()
    {
        return [
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
}
