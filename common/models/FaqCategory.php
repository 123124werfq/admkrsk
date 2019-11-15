<?php

namespace common\models;

use common\modules\log\behaviors\LogBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "db_faq_category".
 *
 * @property int $id_faq_category
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
class FaqCategory extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Категория';
    const VERBOSE_NAME_PLURAL = 'Категории';
    const TITLE_ATTRIBUTE = 'title';

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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_faq_category' => '#',
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqs()
    {
        return $this->hasMany(Faq::class, ['id_faq_category' => 'id_faq_category']);
    }
}
