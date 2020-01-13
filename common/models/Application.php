<?php

namespace common\models;

use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\base\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "db_application".
 *
 * @property int $id_application
 * @property string $name
 * @property string $access_token
 * @property bool $is_active
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Application extends ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Приложение';
    const VERBOSE_NAME_PLURAL = 'Приложения';
    const TITLE_ATTRIBUTE = 'name';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'access_token'], 'required'],
            [['is_active'], 'boolean'],
            [['name'], 'string', 'max' => 255],
            [['access_token'], 'string', 'max' => 32],
            [['access_token'], 'unique'],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_application' => '#',
            'name' => 'Название',
            'access_token' => 'Access Token',
            'is_active' => 'Активно',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
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
     * @return bool
     * @throws Exception
     */
    public function beforeValidate()
    {
        if (empty($this->access_token)) {
            $this->access_token = Yii::$app->security->generateRandomString();
        }

        return parent::beforeValidate();
    }
}
