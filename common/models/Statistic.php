<?php

namespace common\models;

use common\traits\MetaTrait;

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
                'model' => $action['model'],
                'model_id' => $action['model_id'],
                'year' => $action['year'],
            ]);

        if (($statistic = $query->one()) === null) {
            $statistic = new Statistic();
            $statistic->model = $action['model'];
            $statistic->model_id = $action['model_id'];
            $statistic->year = $action['year'];
        }

        $statistic->views = $action['count'];

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
}
