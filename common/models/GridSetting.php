<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property int $id
 * @property string $class
 * @property int $user_id
 * @property string $settings
 */
class GridSetting extends ActiveRecord
{
    public static function getGridColumns($defaultColumns, $customColumns, $modelClass)
    {
        /** @var ActiveRecord $model */
        $model = new $modelClass();
        $gridColumns = [];
        $visibleColumns = [];
        $labels = $model->attributeLabels();
        if ($customColumns) {
            foreach ($customColumns as $column) {
                if (in_array($column['columnName'], $labels)) {
                    $attributeName = array_search($column['columnName'], $labels);
                    if (array_key_exists($attributeName, $defaultColumns)) {
                        if ($column['isVisible']) {
                            $visibleColumns[$column['columnName']] = true;
                            $gridColumns[$attributeName] = $defaultColumns[$attributeName];
                        } else {
                            $visibleColumns[$column['columnName']] = false;
                        }
                    }
                }
            }
        }

        if (empty($gridColumns)) {
            foreach ($defaultColumns as $name => $columns) {
                $label = $model->getAttributeLabel($name);
                $visibleColumns[$label] = true;
            }
            $gridColumns = $defaultColumns;
        }

        return [
            $gridColumns,
            $visibleColumns
        ];
    }

    public function saveSettings($settings)
    {
        ArrayHelper::multisort($settings, 'position', SORT_ASC);
        $this->settings = json_encode($settings);
    }

    public function saveOptions($class, $settings)
    {
        $this->user_id = Yii::$app->user->id;
        $this->class = $class;
        $this->saveSettings($settings);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'grid_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['class', 'settings'], 'string',],
        ];
    }
}
