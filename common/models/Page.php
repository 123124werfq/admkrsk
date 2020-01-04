<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
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
 */
class Page extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Страница';
    const VERBOSE_NAME_PLURAL = 'Страницы';
    const TITLE_ATTRIBUTE = 'title';

    public $access_user_ids;
    public $access_user_group_ids;
    public $existUrl;

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
            [['id_media', 'active', 'id_parent', 'noguest','hidemenu'], 'integer'],
            ['id_parent', 'filter', 'filter' => function($value) {
                return (int) $value;
            }],
            [['title', 'alias'], 'required'],
            [['content','path'], 'string'],
            [['is_partition'], 'boolean'],
            [['alias'], 'unique'],
            [['title', 'alias', 'seo_title', 'seo_description', 'seo_keywords'], 'string', 'max' => 255],

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
            'id_parent' => 'Родительские раздел',
            'alias' => 'URL',
            'content' => 'Содержание',
            'seo_title' => 'Seo Заголовок',
            'seo_description' => 'Seo Описание',
            'seo_keywords' => 'Ключевые слова',
            'noguest' => 'Доступно только авторизованным',
            'active' => 'Активный',
            'is_partition'=>'Это раздел',
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

        $path = explode('/', $this->path);

        if (!empty($path))
        {
            foreach ($path as $key => $slug)
                $path[$key] = (int)$slug;

            $pages = Page::find()->where(['id_page'=>$path])->select(['alias','id_page'])->indexBy('id_page')->all();

            foreach ($path as $key => $slug)
            {
                if (!empty($pages[$slug]))
                    $path[$key] = $pages[$slug]->alias;
            }

            $this->existUrl = (($absolute)?Yii::$app->params['frontendUrl']:'').'/'.implode('/', $path);

            return $this->existUrl;
        }
        else
        {
            if ($this->alias=='/' && $absolute)
                $this->alias = '';

            return (($absolute)?Yii::$app->params['frontendUrl']:'').'/'.$this->alias;
        }
    }

    public function getFullUrl()
    {
        return $this->getUrl(true);
    }

    public function createPath()
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
    }

    public static function getUrlByID($id)
    {
        $page = Page::findOne($id);

        if (!empty($page))
        {
            return $page->getUrl();
        }

        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (isset($changedAttributes['id_parent']) || $insert)
            $this->createPath();

        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeValidate()
    {
        $this->content = str_replace(['&lt;','&gt;','&quote;'], ['<','>','"'], $this->content);

        // удалить после релиза , нужно для заполняторов
        if (!empty($this->created_at) && !is_numeric($this->created_at))
            $this->created_at = strtotime($this->created_at);

        return parent::beforeValidate();
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
        return $this->hasMany(Block::class, ['id_page' => 'id_page_layoyt'])->orderBy('ord ASC');
    }

    /**
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Page::class, ['id_page' => 'id_parent']);
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
}
