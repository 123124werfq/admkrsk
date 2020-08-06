<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\behaviors\MailNotifyBehaviour;
use common\behaviors\NestedSetsBehavior;
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
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cnt_page".
 *
 * @property int $id_page
 * @property int $id_parent
 * @property int $id_media
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property int $active
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property array $access_user_ids
 * @property int $notify_rule
 * @property string $notify_message
 * @property Block[] $blocks
 *
 * @property Collection[] $collections
 */
class Page extends ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Страница';
    const VERBOSE_NAME_PLURAL = 'Страницы';
    const TITLE_ATTRIBUTE = 'title';

    const TYPE_PAGE = 1;
    const TYPE_NEWS = 2;
    const TYPE_ANONS = 3;
    const TYPE_LINK = 4;

    /**
     * Is need to notify the administrator
     *
     * @var boolean
     */
    public $is_admin_notify;

    public $old_parent; //$id_parent,
    public $access_user_ids;
    public $access_user_group_ids;
    public $existUrl;
    //public $treeAttribute = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cnt_page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_media', 'active', 'id_parent'], 'default', 'value' => null],
            [['type'], 'default', 'value' => self::TYPE_PAGE],
            [['id_media', 'active', 'id_parent', 'noguest', 'hidemenu','notify_rule','type'], 'integer'],
            [['is_admin_notify'], 'boolean'],
            [['title', 'alias'], 'required'],
            ['id_parent', 'required', 'when' => function($model) {
                return ($model->lft!=1);
            }],
            [['is_partition'], 'boolean'],
            ['id_parent', 'required', 'when' => function($model) {
                return ($model->lft!=1);
            }],
            [['content', 'path', 'notify_message', 'hidden_message','label'], 'string'],
            [['alias'], 'unique'],
            [['title', 'alias', 'seo_title', 'seo_description', 'seo_keywords'], 'string', 'max' => 255],
            [['partition_domain'], 'url', 'defaultScheme' => 'http'],
            [['created_at'],'safe'],
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
            'id_page' => '#',
            'id_media' => 'Id Media',
            'title' => 'Название',
            'id_parent' => 'Родительская страница',
            'alias' => 'URL',
            'label' =>'Заголовок для навигации',
            'hidemenu' => 'Скрыть в меню',
            'content' => 'Содержание',
            'seo_title' => 'Seo Заголовок',
            'seo_description' => 'Seo Описание',
            'seo_keywords' => 'Ключевые слова',
            'noguest' => 'Доступно только авторизованным',
            'active' => 'Активный',
            'type' => 'Тип страницы',
            'hidden_message'=>'Сообщение при закрытом разделе',
            'is_partition'=>'Это раздел',
            'partition_domain'=>'Домен раздела',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Обновил',
            'deleted_at' => 'Удалено',
            'deleted_by' => 'Удалил',
        ];
    }

    public function getTypes()
    {
        $labels = [
            self::TYPE_PAGE => "Страница",
            self::TYPE_NEWS => "Новости",
            self::TYPE_ANONS => "Анонсы",
            //self::TYPE_LINK => "Ссылка",
        ];

        return $labels;
    }

    public function isNews()
    {
        if ($this->type == self::TYPE_NEWS || $this->type == self::TYPE_ANONS)
            return true;

        return false;
    }

    public function getLabel()
    {
        if (!empty($this->label))
            return $this->label;

        return $this->title;
    }

    public function getUrl($absolute = false)
    {
        if (!empty($this->existUrl))
            return $this->existUrl;

        if ($this->isNewRecord)
            return '';

        if ($this->is_partition && !empty($this->partition_domain))
            return $this->partition_domain;

        $pages = $this->parents()->select(['alias','partition_domain','is_partition'])->asArray()->all();
        $pages[] = $this;

        $domain = '';
        $url = [];

        foreach ($pages as $key => $page)
        {
            if (!empty($page['is_partition']) && !empty($page['partition_domain']))
                $domain = $page['partition_domain'];

            if ($page['alias']!='/')
                $url[] = $page['alias'];
        }

        if (empty($domain) && $absolute)
            $domain = Yii::$app->params['frontendUrl'];

        if (!empty($url))
            $domain .= '/';

        $this->existUrl = $domain.implode('/', $url);

        return $this->existUrl;
    }

    public function getFullUrl()
    {
        return $this->getUrl(true);
    }


    public static function getUrlByID($id)
    {
        $page = Page::findOne($id);

        if (!empty($page)) {
            return $page->getUrl();
        }

        return false;
    }

    public function getPartition()
    {
        return $this->parents()->andWhere('is_partition = TRUE')->orderBy('lft DESC')->one();
    }

    public function beforeValidate()
    {
        // конвертирует данные от TinyMCE
        if (!empty($_POST))
            $this->content = str_replace(['&lt;','&gt;','&quote;'], ['<','>','"'], $this->content);

        // удалить после релиза , нужно для заполняторов
        if (!empty($this->created_at) && !is_numeric($this->created_at))
            $this->created_at = strtotime($this->created_at);

        return parent::beforeValidate();
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
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
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.page',
            ],
            'tree'=>[
                'class' => NestedSetsBehavior::class,
            ],
            'afterUpdateMailNotify' => [
                'class' => MailNotifyBehaviour::class,
                'userIds' => 'access_user_ids',
                'isAdminNotify' => 'is_admin_notify',
                'timeRuleAttribute' => 'notify_rule',
                'messageAttribute' => 'notify_message',
            ],
            'multiupload' => [
                'class' => MultiUploadBehavior::class,
                'relations' =>
                    [
                        'medias' => [
                            'model' => 'Media',
                            'jtable' => 'dbl_page_media',
                        ],
                    ],
            ],
        ];
    }

    public function beforeSave($insert)
    {
        $this->updateCollectionPluginSettings();
        return parent::beforeSave($insert);
    }

    /**
     * Remove collection setting plugins by keys
     */
    private function updateCollectionPluginSettings()
    {
        $oldCollectionSettingsKeys = $this->searchCollectionKeys($this->getOldAttribute('content'));
        $newCollectionSettingsKeys = $this->searchCollectionKeys($this->getAttribute('content'));
        $deleteKeys = array_diff($oldCollectionSettingsKeys, $newCollectionSettingsKeys);
        SettingPluginCollection::deleteAll([
            'key' => $deleteKeys,
        ]);
    }

    /**
     * @param string $content
     * @return array
     */
    private function searchCollectionKeys($content)
    {
        $collectionSettingsKeys = [];

        if (!empty($content))
            if (preg_match_all('/data-key=".*"/i', $content, $matches)) {
                foreach ($matches[0] as $match) {
                    $key = preg_split('/data-key=/i', $match);
                    $collectionSettingsKeys[] = str_replace('"', '', $key[1]);
                }
            }
        return $collectionSettingsKeys;
    }

    public static function find()
    {
        $query = new PageQuery(get_called_class());
        return $query->active();
    }

    public function getMedias()
    {
        return $this->hasMany(Media::class, ['id_media' => 'id_media'])->viaTable('dbl_page_media', ['id_page' => 'id_page']);
    }

    /**
     * @return ActiveQuery
     */
    public function getBlocks()
    {
        return $this->hasMany(Block::class, ['id_page' => 'id_page'])->orderBy('ord ASC');
    }

    /**
     * @return ActiveQuery
     */
    public function getBlocksLayout()
    {
        return $this->hasMany(Block::class, ['id_page_layout' => 'id_page'])->orderBy('ord ASC');
    }

    /**
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->parents(1)->one();
        //return $this->hasOne(Page::class, ['id_page' => 'id_parent']);
    }

    /**
     * @return ActiveQuery
     */
    public function getMenu()
    {
        return $this->hasOne(Menu::class, ['id_page' => 'id_page']);
    }

    /**
     * @return ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(Page::class, ['id_parent' => 'id_page'])->orderBy('ord ASC');
    }

    /*public function getSubMenu()
    {
        $childs = $this->getChilds()->orderBy('ord ASC')->all();
        $links = [];

        if (!empty($this->menu))
            $links  = $this->menu->links;


        foreach ($childs as $key => $child)
            $rightmenu[] = $child;

        foreach ($links as $key => $link)
            $rightmenu[] = $link;

        if (empty($rightmenu))
            return [];

        usort($rightmenu, function ($a, $b)
        {
            if ($a->ord == $b->ord)
            {
                return 0;
            }
            return ($a->ord < $b->ord)?-1:1;
        });

        return $rightmenu;
    }*/

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::class, ['id_collection' => 'id_collection'])
            ->viaTable('dbl_collection_page', ['id_page' => 'id_page']);
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
                $hasAccess = !empty(self::getAccessPageIds());
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
                $hasEntityAccess = in_array($entity_id, self::getAccessPageIds());
            } else {
                $hasEntityAccess = !empty(self::getAccessPageIds());
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

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public static function getAccessPageIds()
    {
        $cacheKey = self::entityIdsCacheKey();

        if (User::rbacCacheIsChanged($cacheKey)) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . mb_strtolower(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $entityIds = null;
            } else {
                $entityIds = [];

                $pageQuery = Page::find();

                $pageIds = self::getAccessEntityIds();

                if (is_array($pageIds) && !empty($pageIds)) {
                    $pageQuery->andFilterWhere(['id_page' => $pageIds]);
                } else {
                    $pageQuery->andWhere(['id_page' => $pageIds]);
                }

                if (Yii::$app->authManager->checkAccess($userId, 'admin.cstProfile')) {
                    if ($contests = Page::findOne(['alias' => 'contests'])) {
                        $pageQuery->orWhere([
                            'or',
                            ['id_page' => $contests->id_page],
                            ['id_page' => $contests->children()->select('id_page')]
                        ]);
                    }
                }

                foreach ($pageQuery->each() as $page) {
                    /* @var Page $page */
                    $entityIds[$page->id_page] = $page->id_page;

                    foreach ($page->children()->each() as $childPage) {
                        /* @var Page $childPage */
                        $entityIds[$childPage->id_page] = $childPage->id_page;
                    }
                }
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
