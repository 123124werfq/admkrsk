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

/**
 * This is the model class for table "db_faq".
 *
 * @property int $id_faq
 * @property string $status
 * @property string $question
 * @property string $answer
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property string $statusName
 * @property array $id_faq_categories
 *
 * @property FaqFaqCategory[] $faqFaqCategory
 * @property FaqCategory[] $categories
 */
class Faq extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Вопросы и ответы';
    const VERBOSE_NAME_PLURAL = 'Вопросы и ответы';
    const TITLE_ATTRIBUTE = 'question';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    public $id_faq_categories;

    public $access_user_ids;
    public $access_user_group_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_faq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_faq_categories', 'status', 'question', 'answer'], 'required'],
            [['status'], 'integer'],
            [['question', 'answer'], 'string'],
            [['id_faq_categories'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => FaqCategory::class, 'targetAttribute' => 'id_faq_category']],

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
            'id_faq' => '#',
            'id_faq_categories' => 'Категории',
            'status' => 'Статус',
            'question' => 'Вопрос',
            'answer' => 'Ответ',
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
                'permission' => 'backend.faq',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->id_faq_categories) {
            $this->unlinkAll('categories');
            foreach (FaqCategory::findAll($this->id_faq_categories) as $category) {
                /* @var FaqCategory $category */
                $this->link('categories', $category);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqFaqCategory()
    {
        return $this->hasMany(FaqFaqCategory::class, ['id_faq' => 'id_faq']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(FaqCategory::class, ['id_faq_category' => 'id_faq_category'])
            ->via('faqFaqCategory');
    }

    /**
     * Возвращает массив статусов
     *
     * @return array
     */
    public static function getStatusNames()
    {
        return [
            self::STATUS_ACTIVE => 'Активен',
            self::STATUS_INACTIVE => 'Скрыт',
            self::STATUS_DELETED => 'Удален',
        ];
    }

    /**
     * Возвращает название статуса
     *
     * @return string
     */
    public function getStatusName()
    {
        $statuses = self::getStatusNames();

        if ($statuses[$this->status]) {
            return $statuses[$this->status];
        }

        return null;
    }
}
