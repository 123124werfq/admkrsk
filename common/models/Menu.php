<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

use common\components\yiinput\RelationBehavior;
use common\modules\log\behaviors\LogBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "db_menu".
 *
 * @property int $id_menu
 * @property string $alias
 * @property string $name
 * @property int $state
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property array $access_user_ids
 */
class Menu extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Меню';
    const VERBOSE_NAME_PLURAL = 'Меню';
    const TITLE_ATTRIBUTE = 'name';

    const TYPE_LIST = 0;
    const TYPE_TABS = 1;
    const TYPE_LEVELS = 2;

    public $access_user_ids;
    public $access_user_group_ids;

    public $types = [
        self::TYPE_LIST => 'Список',
        self::TYPE_TABS => 'Табы',
        self::TYPE_LEVELS => 'Двухуровневое',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'type'], 'integer'],
            [['alias', 'name'], 'string', 'max' => 255],

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
            'id_menu' => 'ID',
            'alias' => 'Алиас',
            'name' => 'Название',
            'state' => 'Состояние',
            'type' => 'Тип',
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
            'log' => LogBehavior::class,
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.menu',
            ],
            'yiinput' => [
                'class' => RelationBehavior::class,
                'relations'=> [
                    'links'=>[
                        'modelname'=> 'MenuLink',
                        'added'=>true,
                    ],
                ]
            ]
        ];
    }

    public function addLink($page,$ord=null)
    {
        $link = new MenuLink;
        $link->id_menu = $this->id_menu;
        $link->id_page = $page->id_page;
        $link->state = $page->hidemenu;
        $link->label = $page->title;
        $link->ord = ($ord)?$ord:$this->getLinks()->count();
        $link->save();
    }

    public function getLinks()
    {
        return $this->hasMany(MenuLink::class, ['id_menu' => 'id_menu'])->orderBy('ord ASC');
    }
}
