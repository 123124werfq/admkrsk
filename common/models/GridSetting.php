<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Responsible for grid settings.
 *
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
        $labels = static::getLabels($defaultColumns, $model);
        if ($customColumns) {

            /** if you added new columns when custom column save,
             * add new columns to the end.
             */
            if (count($customColumns) !== count($defaultColumns)) {
                $customColumns = static::getDiff($customColumns, $defaultColumns, $labels);
            }

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
                if (preg_match('/.+\..+/i', $name) || preg_match('/.*:prop/i', $name)) {
                    $label = $columns['label'];
                }
                $visibleColumns[$label] = true;
            }
            $gridColumns = $defaultColumns;
        }

        return [
            $gridColumns,
            $visibleColumns
        ];
    }

    /**
     * Add new columns for existing columns
     *
     * @param array $oldColumns
     * @param array $newColumns
     * @param array $labels
     * @return array
     */
    private static function getDiff($oldColumns, $newColumns, $labels)
    {
        $oldColumnsKeys = array_keys($oldColumns);
        $newColumnsKeys = array_flip(array_keys($newColumns));
        $newsElements = array_diff($newColumnsKeys, $oldColumnsKeys);
        foreach ($newsElements as $name => $index) {
            $columnName = '';
            if (array_key_exists($name, $labels)) {
                $columnName = $labels[$name];
            }
            if ($columnName) {
                $oldColumns[] = [
                    'columnName' => $columnName,
                    'isVisible' => true,
                ];
            }
        }
        return $oldColumns;
    }

    /**
     * @param array $defaultColumns
     * @param ActiveRecord $model
     * @return array
     */
    private static function getLabels($defaultColumns, $model)
    {
        $labels = $model->attributeLabels();
        $externalLabels = [];
        foreach ($defaultColumns as $name => $columns) {
                            /** prop = trait-property or relation  */
            if (preg_match('/.+\..+/i', $name) || preg_match('/.*:prop/i', $name)) {
                $externalLabels[$name] = $columns['label'];
            }
        }
        return $externalLabels + $labels;
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
