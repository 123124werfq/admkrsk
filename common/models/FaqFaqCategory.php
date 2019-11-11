<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_faq_faq_category".
 *
 * @property int $id_faq
 * @property int $id_faq_category
 *
 * @property Faq $faq
 * @property FaqCategory $faqCategory
 */
class FaqFaqCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_faq_faq_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_faq', 'id_faq_category'], 'required'],
            [['id_faq', 'id_faq_category'], 'default', 'value' => null],
            [['id_faq', 'id_faq_category'], 'integer'],
            [['id_faq'], 'exist', 'skipOnError' => true, 'targetClass' => Faq::class, 'targetAttribute' => ['id_faq' => 'id_faq']],
            [['id_faq_category'], 'exist', 'skipOnError' => true, 'targetClass' => FaqCategory::class, 'targetAttribute' => ['id_faq_category' => 'id_faq_category']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_faq' => 'Id Faq',
            'id_faq_category' => 'Id Faq Category',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaq()
    {
        return $this->hasOne(Faq::class, ['id_faq' => 'id_faq']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(FaqCategory::class, ['id_faq_category' => 'id_faq_category']);
    }
}
