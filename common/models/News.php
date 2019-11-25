<?php

namespace common\models;
use common\behaviors\UserAccessControlBehavior;
use common\modules\log\behaviors\LogBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use dosamigos\taggable\Taggable;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "db_news".
 *
 * @property int $id_news
 * @property int $id_page
 * @property int $id_category
 * @property int $id_rub
 * @property int $id_media
 * @property string $title
 * @property string $description
 * @property string $content
 * @property int $date_publish
 * @property int $date_unpublish
 * @property int $state
 * @property int $main
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property array $access_user_ids
 */
class News extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Новость';
    const VERBOSE_NAME_PLURAL = 'Новости';
    const TITLE_ATTRIBUTE = 'title';

    public $access_user_ids, $tagNames = [];
    public $pages;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_page', 'id_category', 'id_rub', 'id_media', 'date_publish', 'date_unpublish', 'state', 'main', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','id_user'], 'default', 'value' => null],
            [['id_page', 'id_category', 'id_rub', 'id_media', 'state', 'main', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','id_user'], 'integer'],
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['date_publish', 'date_unpublish','tagNames','pages'], 'safe'],
            [['title', 'description'], 'string', 'max' => 255],
            ['access_user_ids', 'each', 'rule' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_news' => '#',
            'id_page' => 'Раздел',
            'id_category' => 'Категория',
            'id_rub' => 'Рубрика',
            'id_user' => 'Автор',
            'id_media' => 'Обложка',
            'title' => 'Заголовок',
            'description' => 'Описание',
            'content' => 'Содержание',
            'date_publish' => 'Дата публикации',
            'date_unpublish' => 'Снять с публикации',
            'state' => 'Статус',
            'pages' => 'Опубликовать в',
            'tagNames'=>'Теги',
            'main' => 'На главную',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'access_user_ids' => 'Доступ',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!empty($_POST['News']))
        {
            $links = Yii::$app->db->createCommand("SELECT * FROM dbl_news_page WHERE id_news = ".$this->id_news)->queryAll();

            $exist = [];
            foreach ($links as $key => $link)
                $exist[$link['id_page']] = $link['id_page'];

            if (!empty($this->pages))
            {
                foreach ($this->pages as $key => $id_page)
                {
                    $page = Page::findOne($id_page);
                    $this->link('pages',$page,['created_at'=>time(),'created_by'=>Yii::$app->user->id]);

                    unset($exist[$id_page]);
                }
            }

            if (!empty($exist))
                Yii::$app->db->delete('dbl_news_page',['id_news'=>$this->id_news,'id_page'=>$exist])->execute();
        }

        parent::afterSave($insert, $changedAttributes);
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
                'class' => UserAccessControlBehavior::class,
                'permission' => 'backend.news',
            ],
            'taggable'=>['class' => Taggable::class],
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

    public function beforeValidate()
    {
        if (!empty($this->date_publish) && !is_numeric($this->date_publish))
            $this->date_publish = strtotime($this->date_publish);

        if (!empty($this->date_unpublish) && !is_numeric($this->date_unpublish))
            $this->date_unpublish = strtotime($this->date_unpublish);

        return parent::beforeValidate();
    }

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'id_tag'])->viaTable('dbl_news_tag', ['id_news' => 'id_news']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRub()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_rub']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CollectionRecord::class, ['id_category' => 'id_category']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::class, ['id_page' => 'id_page']);
    }

     public function getPages()
    {
        return $this->hasOne(Page::class, ['id_page' => 'id_page'])->viaTable('dbl_news_page', ['id_news' => 'id_news']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasOne(Media::class, ['id_media' => 'id_media']);
    }

    /**
     * @return string
     */
    public function getUrl($absolute=false)
    {
        if (!empty($this->page))
            return $this->page->getUrl($absolute).'?id='.$this->id_news;

        return '/news?id='.$this->id_news;
    }

    public function getFullUrl()
    {
        return $this->getUrl(true);
    }
}
