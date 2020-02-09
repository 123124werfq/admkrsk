<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\behaviors\NestedSetsBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\models\db\MultilangActiveRecord;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "db_faq_category".
 *
 * @property int $id_faq_category
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property int $id_parent
 * @property string $title
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property Faq[] $faqs
 */
class FaqCategory extends ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Категория';
    const VERBOSE_NAME_PLURAL = 'Категории';
    const TITLE_ATTRIBUTE = 'title';

    public $access_user_ids;
    public $access_user_group_ids;

    public $id_parent;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_faq_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 255],

            [['id_parent'], 'integer'],
            [['id_parent'], 'exist', 'targetClass' => FaqCategory::class, 'targetAttribute' => 'id_faq_category'],

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
            'id_faq_category' => '#',
            'id_parent' => 'Родительская категория',
            'title' => 'Название',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Обновил',
            'deleted_at' => 'Удалено',
            'deleted_by' => 'Удалил',
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
                'permission' => 'backend.faqCategory',
            ],
            'tree'=>[
                'class' => NestedSetsBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        $query = new FaqCategoryQuery(get_called_class());
        return $query->active();
    }

    /**
     * @return ActiveQuery
     */
    public function getFaqs()
    {
        return $this->hasMany(Faq::class, ['id_faq_category' => 'id_faq_category']);
    }

    /**
     * @param int|null $without_id этот id игнорировать (в выпадающем списке мы не можем выбрать себя в качестве родителя)
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTree($without_id = null)
    {
        /** @var FaqCategory $root */
        $root = FaqCategory::find()->roots()->one();
        /* @var ActiveQuery $query */
        $query = $root->children();

        if ($without_id && ($without = FaqCategory::findOne($without_id)) !== null) {
            $query->andWhere([
                'or',
                ['<', 'lft', $without->lft],
                ['>', 'lft', $without->rgt],
            ]);
        }

        $result = [];
        foreach ($query->asArray()->all() as $child) {
            $result[$child['id_faq_category']] = str_repeat('—', $child['depth'] - 1) . ' ' . $child['title'];
        }
        return $result;
    }
}
