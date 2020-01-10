<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;

use common\traits\AccessTrait;
use creocoder\nestedsets\NestedSetsBehavior;

use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "cnt_page".
 *
 * @property int $id_page
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
 * @property Block[] $blocks
 *
 * @property Collection[] $collections
 */
class Page extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Страница';
    const VERBOSE_NAME_PLURAL = 'Страницы';
    const TITLE_ATTRIBUTE = 'title';

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
            [['id_media', 'active'], 'default', 'value' => null],
            [['id_media', 'active', 'id_parent', 'noguest','hidemenu', 'old_parent'], 'integer'],
            /*['id_parent', 'filter', 'filter' => function($value) {
                return (int) $value;
            }],*/
            [['id_parent'], 'required', 'when' => function($model) {
                return $model->alias != '/';
            }],
            [['title', 'alias'], 'required'],
            [['content','path'], 'string'],
            [['is_partition'], 'boolean'],
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
            'content' => 'Содержание',
            'seo_title' => 'Seo Заголовок',
            'seo_description' => 'Seo Описание',
            'seo_keywords' => 'Ключевые слова',
            'noguest' => 'Доступно только авторизованным',
            'active' => 'Активный',
            'is_partition'=>'Это раздел',
            'partition_domain'=>'Домен раздела',
            'hidemenu'=> 'Скрыть в меню',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Обновил',
            'deleted_at' => 'Удалено',
            'deleted_by' => 'Удалил',
        ];
    }

    public function getUrl($absolute=false)
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

    /*public function createPath()
    {
        $oldpath = $this->path;

        if (empty($this->id_parent))
            $this->path = $this->id_page;
        else
            $this->path = $this->parent->path.'/'.$this->id_page;

        $this->updateAttributes(['path']);

        $sql = "UPDATE cnt_page SET path = REPLACE(path,'$oldpath','$this->path') WHERE path LIKE '$oldpath/%'";
        //Yii::$app->db->createCommand()->update('cnt_page','path = REPLACE(path,$oldpath)');
        Yii::$app->db->createCommand($sql)->execute();
    }*/

    public static function getUrlByID($id)
    {
        $page = Page::findOne($id);

        if (!empty($page))
            return $page->getUrl();

        return false;
    }

    /*public function afterSave($insert, $changedAttributes)
    {
        //if (isset($changedAttributes['id_parent']) || $insert)

        //$this->createPath();

        parent::afterSave($insert, $changedAttributes);
    }*/

    public function beforeValidate()
    {
        // концертирует данные от TinyMCE
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
                'class' => NestedSetsBehavior::className(),
            ],
            'multiupload' => [
                'class' => \common\components\multifile\MultiUploadBehavior::class,
                'relations'=>
                [
                    'medias'=>[
                        'model'=>'Media',
                        'jtable'=>'dbl_page_media',
                    ],
                ],
            ],
        ];
    }

    public static function find()
    {
        return new PageQuery(get_called_class());
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
        return $this->children(1);
        //return $this->hasMany(Page::class, ['id_parent' => 'id_page'])->orderBy('ord ASC');
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
     * @throws \yii\base\InvalidConfigException
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::class, ['id_collection' => 'id_collection'])
            ->viaTable('dbl_collection_page', ['id_page' => 'id_page']);
    }

    public static function hasAccess()
    {
        return !empty(self::getAccessPageIds());
    }

    /**
     * @return array
     */
    public static function getAccessPageIds()
    {
        $pageIds = [];

        $partitionQuery = Page::find()
            ->andWhere([
                'id_page' => self::getAccessIds(),
                'is_partition' => true,
            ]);

        foreach ($partitionQuery->each() as $partition) {
            /* @var Page $partition */
            $pageIds[$partition->id_page] = $partition->id_page;

            foreach ($partition->children()->each() as $childPartition) {
                /* @var Page $childPartition */
                $pageIds[$childPartition->id_page] = $childPartition->id_page;
            }
        }

        return $pageIds;
    }
}
