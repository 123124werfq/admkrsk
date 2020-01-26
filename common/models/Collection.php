<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\behaviors\MailNotifyBehaviour;
use common\components\collection\CollectionQuery;
use common\components\yiinput\RelationBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "db_collection".
 *
 * @property int $id_collection
 * @property string $name
 * @property string $alias
 * @property string $is_dictionary
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property int $id_parent_collection
 * @property int $id_form
 * @property array $label
 * @property int $id_box
 * @property int $system
 * @property array $filter
 * @property array $options
 * @property string $template
 * @property string $template_element
 * @property array $access_user_ids
 * @property bool $is_authenticate
 * @property int $notify_rule
 * @property string $notify_message
 *
 * @property CollectionColumn[] $columns
 */
class Collection extends ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Список';
    const VERBOSE_NAME_PLURAL = 'Списки';
    const TITLE_ATTRIBUTE = 'name';

    /**
     * Is need to notify the administrator
     *
     * @var boolean
     */
    public $is_admin_notify;

    /**
     * User ids having access for edit that collection
     */
    public $access_user_ids;
    public $access_user_group_ids;

    public $template_view = 'table';
    public $id_column_order = null;
    public $pagesize = 20;
    public $order_direction = SORT_DESC;

    public $show_row_num;
    public $show_column_num;
    public $show_on_map;
    public $show_download;
    public $link_column;

    public $table_head;
    public $table_style;

    public $id_partitions = [];

    public $id_page;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alias'], 'unique'],
            [['name'], 'required'],
            [['name', 'alias'], 'string', 'max' => 255],
            [['id_parent_collection','id_box','id_column_order','order_direction','pagesize','show_row_num','show_column_num', 'notify_rule', 'show_on_map', 'id_column_map', 'link_column','show_download'], 'integer'],
            [['filter', 'options','label','table_head', 'table_style'], 'safe'],
            [['template','template_element','template_view','notify_message'], 'string'],
            [['is_authenticate'], 'boolean'],
            [['is_admin_notify'], 'boolean'],
            [['is_authenticate'], 'default', 'value' => true],
            [['access_user_ids', 'access_user_group_ids'], 'each', 'rule' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            [
                'access_user_group_ids',
                'each',
                'rule' => ['exist', 'targetClass' => UserGroup::class, 'targetAttribute' => 'id_user_group']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_collection' => '#',
            'name' => 'Название',
            'alias' => 'Алиас',
            'label' => 'Настройка отображения в списках',
            'id_form' => 'Форма редактирования',
            'id_parent_collection' => 'Это справочник',
            'is_dictionary' => 'Это справочник',
            'template' => 'Шаблон для страницы',
            'template_view' => 'Вывод в разделе',
            'template_element' => 'Шаблон для элемента',
            'id_box' => 'Группа',
            'id_column_order' => 'Сортировать по',
            'order_direction' => 'Направление сортировки',
            'is_authenticate' => 'Авторизация (API)',

            'pagesize'=>'Элементов на страницу',
            'show_column_num'=>'Показывать номер столбца',
            'show_row_num'=>'Показывать номер строки',
            'show_on_map'=>'Показать на карте',
            'id_column_map'=>'Колонка координат для показа на карте',
            'table_head'=>'Шапка таблицы',
            'table_style'=>'Стиль таблицы',
            'show_download'=>'Показывать ссылку для скачивания',

            'created_at' => 'Создана',
            'created_by' => 'Кем создана',
            'updated_at' => 'Изменено',
            'updated_by' => 'Кем отредактирована',
            'deleted_at' => 'Удалена',
            'deleted_by' => 'Кем удалена',
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
                'permission' => 'backend.collection',
            ],
            'yiinput' => [
                'class' => RelationBehavior::class,
                'relations'=> [
                    'partitions'=>[
                        'modelname'=>'Page',
                        'jtable'=>'dbl_collection_page',
                        'added'=>false,
                    ],
                ]
            ],
            'afterUpdateMailNotify' => [
                'class' => MailNotifyBehaviour::class,
                'userIds' => 'access_user_ids',
                'isAdminNotify' => 'is_admin_notify',
                'timeRuleAttribute' => 'notify_rule',
                'messageAttribute' => 'notify_message',
            ],
        ];
    }

    public function insertRecord($data)
    {
        $model = new CollectionRecord;
        $model->id_collection = $this->id_collection;
        $model->data = $data;

        if ($model->save()) {
            return $model;
        }

        return false;
    }

    public function getForm()
    {
        return $this->hasOne(Form::class, ['id_form' => 'id_form']);
    }

    public function getPartitions()
    {
        return $this->hasMany(Page::class, ['id_page' => 'id_page'])->viaTable('dbl_collection_page',['id_collection'=>'id_collection']);
    }

    public function getParent()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_parent_collection']);
    }

    public function getItems()
    {
        return $this->hasMany(CollectionRecord::class, ['id_collection' => 'id_collection'])->orderBy('ord ASC');
    }

    /**
     * @return ActiveQuery
     */
    public function getColumns()
    {
        if (empty($this->id_parent_collection))
        {
            return $this->hasMany(CollectionColumn::class, ['id_collection' => 'id_collection'])->orderBy('ord ASC');
        }
        else
        {
            return $this->hasMany(CollectionColumn::class,
                ['id_collection' => 'id_parent_collection'])->orderBy('ord ASC');
        }
    }

    public function getArray($id_column = null)
    {
        if (!empty($id_column)) {
            $label = [$id_column];
        } else {
            $label = (!empty($this->label)) ? $this->label : [];
        }

        $data = $this->getData($label);

        $output = [];

        foreach ($data as $key => $row)
            $output[$key] = implode(' ', $row);

        return $output;
    }

    public static function getArrayByAlias($alias)
    {
        $collection = Collection::find()->where(['alias' => $alias])->one();

        if (empty($collection)) {
            return [];
        }

        return $collection->getArray();
    }

    public function getData($id_columns = [], $keyAsAlias = false)
    {
        if (!empty($this->id_parent_collection)) {
            $id_collection = $this->id_parent_collection;
        } else {
            $id_collection = $this->id_collection;
        }

        $query = CollectionQuery::getQuery($id_collection);

        if (!empty($this->options)) {
            $options = json_decode($this->options, true);

            if (!empty($options['filters'])) {
                foreach ($options['filters'] as $key => $filter) {
                    $where = [$filter['operator'], $filter['id_column'], $filter['value']];
                    if ($key == 0) {
                        $query->where($where);
                    } else {
                        $query->andWhere($where);
                    }
                }
            }
        }

        $query->keyAsAlias = $keyAsAlias;

        return $query->select($id_columns)->getArray();
    }

    public function getDataQuery()
    {
        if (!empty($this->id_parent_collection)) {
            $id_collection = $this->id_parent_collection;
        } else {
            $id_collection = $this->id_collection;
        }

        $query = CollectionQuery::getQuery($id_collection)->select();

        if (!empty($this->options))
        {
            $options = json_decode($this->options, true);

            if (!empty($options['filters']))
            {
                foreach ($options['filters'] as $key => $filter)
                {
                    $where = [$filter['operator'], 'col'.$filter['id_column'],(is_numeric($filter['value']))?(float)$filter['value']:$filter['value']];

                    $query->andWhere($where);
                }
            }
        }

        return $query;
    }

    // пробный метод работы через модели MSD
    public static function getRecordsByData($data)
    {
        $ids = [];
        foreach ($data as $id_record => $row)
            $ids[] = $id_record;

        $records = CollectionRecord::find()->where(['id_record'=>$ids])->indexBy('id_record')->all();

        foreach ($records as $id_record => $record)
            $record->loadDataAlias = $data[$id_record];

        return $records;
    }

    /*public function getRecords($options)
    {

    }*/

    public function getDataQueryByOptions($options, array $search_columns = [])
    {
        if (!empty($this->id_parent_collection)) {
            $id_collection = $this->id_parent_collection;
        } else {
            $id_collection = $this->id_collection;
        }

        $query = CollectionQuery::getQuery($id_collection);

        if (!is_array($options)) {
            $options = json_decode($this->options, true);
        }

        $id_cols = [];

        if (!empty($options['columns'])) {
            foreach ($options['columns'] as $key => $col) {
                $id_cols[] = $col['id_column'];
            }

            if (!empty($options['search'])) {
                foreach ($options['search'] as $key => $col) {
                    $id_cols[] = $col['id_column'];
                }
            }

            $query->select($id_cols);
        } else {
            $query->select();
        }

        if (!empty($this->id_parent_collection)) {
            $options = json_decode($this->options, true);
        }

        if (!empty($options['filters'])) {
            foreach ($options['filters'] as $key => $filter) {
                $where = [
                    $filter['operator'],
                    'col' . $filter['id_column'],
                    (is_numeric($filter['value'])) ? (float)$filter['value'] : $filter['value']
                ];
                $query->andWhere($where);
            }
        }

        return $query;
    }

    public function getGroup()
    {
        return $this->hasOne(Box::class, ['id_box' => 'id_box']);
    }

    public function getViewFilters()
    {
        $options = json_decode($this->options, true);

        if (isset($options['filters'])) {
            return $options['filters'];
        }

        return [];
    }

    public function getViewColumns()
    {
        $options = json_decode($this->options, true);

        if (isset($options['columns'])) {
            return $options['columns'];
        }

        $options = [];

        foreach ($this->parent->columns as $key => $column) {
            $options[] = [
                'id_column' => $column->id_column,
                'value' => '',
            ];
        }

        return $options;
    }

    public function getSearchColumns()
    {
        $options = json_decode($this->options, true);

        if (isset($options['search'])) {
            return $options['search'];
        }

        /*$options = [];

        foreach ($this->parent->columns as $key => $column)
        {
            $options[] = [
                'id_column'=>$column->id_column,
                'type'=>0,
            ];
        }*/

        return [
            [
                'id_column' => '',
                'type' => 0,
            ]
        ];
    }

    public function createColumn($attributes)
    {
        $newColumn = new CollectionColumn;
        $newColumn->name = $attributes['name'];
        $newColumn->alias = $attributes['alias'];
        $newColumn->id_collection = $this->id_collection;
        $newColumn->type = $attributes['type'];

        if ($newColumn->save()) {
            return $newColumn;
        }

        return false;
    }

    public function getApiUrl()
    {
        return Url::to(['/api/collection/index', 'alias' => $this->alias], true);
    }

    public function createForm()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $form = new Form;
            $form->id_collection = $this->id_collection;
            $form->name = $this->name;

            if ($form->save())
            {
                foreach ($this->columns as $ckey => $column)
                {
                    $input = new FormInput;
                    $input->label       = $input->name = $column->name;
                    $input->type        = $column->type;

                    if ($input->type==CollectionColumn::TYPE_CHECKBOX)
                        $input->values = 1;

                    $input->id_form     = $form->id_form;
                    $input->id_column   = $column->id_column;
                    $input->fieldname   = $column->alias;

                    if (!$input->save())
                        print_r($input->errors);

                    $row = new FormRow;
                    $row->id_form = $form->id_form;

                    if (!$row->save())
                        print_r($row->errors);

                    $element = new FormElement;
                    $element->id_row = $row->id_row;
                    $element->id_input = $input->id_input;

                    if (!$element->save())
                        print_r($element->errors);
                }

                $this->id_form = $form->id_form;
                $this->updateAttributes(['id_form']);
            }

            $transaction->commit();

            $form->createAction(Action::ACTION_CREATE);
        }
        catch (\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
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
                $hasAccess = !empty(self::getAccessCollectionIds());
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
                $hasEntityAccess = in_array($entity_id, self::getAccessCollectionIds());
            } else {
                $hasEntityAccess = !empty(self::getAccessCollectionIds());
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

    public static function getAccessCollectionIds()
    {
        $cacheKey = self::entityIdsCacheKey();

        if (User::rbacCacheIsChanged($cacheKey)) {
            $userId = Yii::$app->user->identity->id;
            $permissionName = 'admin.' . mb_strtolower(StringHelper::basename(self::class));

            if (Yii::$app->authManager->checkAccess($userId, $permissionName)) {
                $entityIds = null;
            } else {
                $collectionQuery = (new Query())
                    ->from('dbl_collection_page')
                    ->select('id_collection');

                $pageIds = Page::getAccessPageIds();

                if (is_array($pageIds) && !empty($pageIds)) {
                    $collectionQuery->andFilterWhere(['id_page' => $pageIds]);
                } else {
                    $collectionQuery->andWhere(['id_page' => $pageIds]);
                }

                $entityIds = array_unique(ArrayHelper::merge($collectionQuery->column(), self::getAccessEntityIds()));
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
