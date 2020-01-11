<?php

namespace common\models;

use common\traits\AccessTrait;
use common\traits\MetaTrait;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "db_statistic".
 *
 * @property int $id_statistic
 * @property string $model
 * @property int $model_id
 * @property int $year
 * @property int $views
 * @property int $created_at
 * @property int $updated_at
 */
class Statistic extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use AccessTrait;

    const VERBOSE_NAME_PLURAL = 'Статистика';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_statistic';
    }

    /**
     * @param array $action
     */
    public static function createOrUpdate(array $action)
    {
        $query = self::find()
            ->andFilterWhere([
                'model' => $action['model'] ?? null,
                'model_id' => $action['model_id'] ?? null,
                'year' => $action['year'] ?? null,
            ]);

        if (($statistic = $query->one()) === null) {
            $statistic = new Statistic();
            $statistic->model = $action['model'] ?? null;
            $statistic->model_id = $action['model_id'] ?? null;
            $statistic->year = $action['year'] ?? null;
        }

        $statistic->views = $action['count'] ?? null;

        $statistic->save();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'model_id'], 'required'],
            [['model_id', 'year', 'views'], 'integer'],
            [['year'], 'default', 'value' => null],
            [['model'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_statistic' => '#',
            'model' => 'Модель',
            'model_id' => 'Название',
            'year' => 'Год',
            'views' => 'Просмотры',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }
}
