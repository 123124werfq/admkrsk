<?php

namespace common\models;
use common\behaviors\AccessControlBehavior;
use common\behaviors\SubscribeBehaviour;
use common\components\multifile\MultiUploadBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use dosamigos\taggable\Taggable;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

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
 * @property int $id_user_contact
 * @property int $id_user
 * @property int $id_record_contact
 * @property int $highlight
 * @property string $fullUrl
 * @property array $access_user_ids
 *
 * @property Media $media
 */
class News extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Новость';
    const VERBOSE_NAME_PLURAL = 'Новости';
    const TITLE_ATTRIBUTE = 'title';

    public $access_user_ids;
    public $access_user_group_ids;

    public $tagNames = [];
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
            [['id_page', 'id_category', 'id_rub', 'id_media', 'date_publish', 'date_unpublish', 'state', 'main', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'url' ,'id_user','id_record_contact'], 'default', 'value' => null],
            [['id_page', 'id_category', 'id_rub', 'id_media', 'state', 'main', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','id_user','id_record_contact', 'highlight'], 'integer'],
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['send_subscribe'],'boolean'],
            [['date_publish', 'date_unpublish','tagNames','pages'], 'safe'],
            [['title', 'description', 'url'], 'string', 'max' => 255],
            [['access_user_ids', 'access_user_group_ids'], 'each', 'rule' => ['integer']],
            [['contacts'],'safe'],
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
            'id_news' => '#',
            'id_page' => 'Раздел',
            'id_category' => 'Категория',
            'id_rub' => 'Рубрика',
            'id_record_contact' => 'Контакт для прессы',
            'id_media' => 'Обложка',
            'title' => 'Заголовок',
            'url' => 'URL',
            'description' => 'Описание',
            'highlight' => 'Выделить',
            'content' => 'Содержание',
            'send_subscribe' => 'Для рассылки',
            'contacts'=>'Контакты для прессы',
            'date_publish' => 'Дата публикации',
            'date_unpublish' => 'Снять с публикации',
            'state' => 'Активен',
            'pages' => 'Опубликовать в',
            'tagNames'=>'Теги',
            'main' => 'На главную',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Отредактировано',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
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
                Yii::$app->db->createCommand()->delete('dbl_news_page',['id_news'=>$this->id_news,'id_page'=>$exist])->execute();
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
            'subscribeNotify' => SubscribeBehaviour::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.news',
            ],
            'taggable'=>['class' => Taggable::class],
            'multiupload' => [
                'class' => MultiUploadBehavior::class,
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
     * @throws InvalidConfigException
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
     *  DEPRECATED NOW IS getContactsRecords!!!! MSD
     * @return ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record_contact']);
    }

    public function getContactsRecords()
    {
        return $this->hasMany(CollectionRecord::class, ['id_record' => 'contacts']);
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
        if (!empty($this->url))
            return $this->url;

        if (!empty($this->page))
            return $this->page->getUrl($absolute).'?id='.$this->id_news;

        return '/news?id='.$this->id_news;
    }

    public function getFullUrl()
    {
        return $this->getUrl(true);
    }

    /**
     * @return array
     * ... [
     *      [
     *          title => 'Новости',
     *          count => 532,
     *      ],
     *      [
     *          title => 'Анонсы',
     *          count => 742,
     *      ],
     *      ....
     * ]
     */
    public static function getSubscriberStatistics()
    {
        return (new Query())
            ->select(['cnt_page.title', 'COUNT(page_id)'])
            ->from('subscriber_subscriptions')
            ->leftJoin('cnt_page', 'subscriber_subscriptions.page_id = cnt_page.id_page')
            ->groupBy('cnt_page.title')
            ->all();
    }

    /**
     * @return array
     * @throws InvalidConfigException
     * ... [
     *          7 => 'Новости',
     *          8 => 'Анонсы',
     *          ....
     *    ]
     */
    public static function getUniqueNews()
    {
        return static::find()
            ->select(
                [
                    'page.title',
                    'news.id_page',
                ])
            ->alias('news')
            ->joinWith('page page', false)
            ->where('news.id_page IS NOT NULL ')
            ->groupBy(['news.id_page', 'page.title'])
            ->indexBy('news.id_page')
            ->asArray()
            ->column();
    }

    public static function hasAccess()
    {
        $cacheKey = self::hasAccessCacheKey();

        if (User::rbacCacheIsChanged($cacheKey)) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . mb_strtolower(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $hasAccess = true;
            } else {
                $hasAccess = !empty(self::getAccessNewsIds());
            }

            Yii::$app->cache->set(
                $cacheKey,
                $hasAccess,
                0,
                User::rbacCacheTag()
            );
        } else {
            $hasAccess = Yii::$app->cache->get($cacheKey);
        }

        return $hasAccess;
    }

    public static function hasEntityAccess($entity_id)
    {
        $cacheKey = self::hasEntityAccessCacheKey($entity_id);

        if (User::rbacCacheIsChanged($cacheKey)) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . mb_strtolower(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $hasEntityAccess = true;
            } elseif ($entity_id) {
                $hasEntityAccess = in_array($entity_id, self::getAccessNewsIds());
            } else {
                $hasEntityAccess = !empty(self::getAccessNewsIds());
            }

            Yii::$app->cache->set(
                $cacheKey,
                $hasEntityAccess,
                0,
                User::rbacCacheTag()
            );
        } else {
            $hasEntityAccess = Yii::$app->cache->get($cacheKey);
        }

        return $hasEntityAccess;
    }

    public static function getAccessNewsIds()
    {
        $cacheKey = self::entityIdsCacheKey();

        if (User::rbacCacheIsChanged($cacheKey)) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . mb_strtolower(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $entityIds = null;
            } else {
                $newsQuery = News::find()
                    ->select('id_news');

                $pageIds = Page::getAccessPageIds();

                if (is_array($pageIds) && !empty($pageIds)) {
                    $newsQuery->andFilterWhere(['id_page' => $pageIds]);
                } else {
                    $newsQuery->andWhere(['id_page' => $pageIds]);
                }

                $entityIds = array_unique(ArrayHelper::merge($newsQuery->column(), self::getAccessEntityIds()));
            }

            Yii::$app->cache->set(
                $cacheKey,
                $entityIds,
                0,
                User::rbacCacheTag()
            );
        } else {
            $entityIds = Yii::$app->cache->get($cacheKey);
        }

        return $entityIds;
    }
}
