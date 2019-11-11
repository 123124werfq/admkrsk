<?php

namespace backend\modules\log\models;

use yii\gii\components\DiffRendererHtmlInline;

/**
 * @property array $changeAttributes
 * @property int $countChanges
 */
class Log extends \common\modules\log\models\Log
{
    /**
     * @return array
     */
    public function getChangeAttributes()
    {
        $changeAttributes = array_merge(array_diff($this->data, $this->previous->data ?? []), array_diff($this->previous->data ?? [], $this->data));

        foreach ($changeAttributes as $attribute => $value) {
            if (!$this->previous && is_null($value)) {
                unset($changeAttributes[$attribute]);
            }
        }

        return $changeAttributes;
    }

    /**
     * @return int
     */
    public function getCountChanges()
    {
        return count($this->changeAttributes);
    }

    /**
     * @return bool
     */
    public function restore()
    {
        if ($this->countChanges) {
            $this->entity->setAttributes($this->data);

            return $this->entity->save();
        }

        return false;
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getEntityAttributeLabel($attribute)
    {
        return (new $this->model)->getAttributeLabel($attribute);
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function diff($attribute)
    {
        return $this->renderDiff($this->previous->data[$attribute] ?? null, $this->data[$attribute]);
    }

    /**
     * @param mixed $lines1
     * @param mixed $lines2
     * @return string
     */
    private function renderDiff($lines1, $lines2)
    {
        if (!is_array($lines1)) {
            $lines1 = explode("\n", $lines1);
        }
        if (!is_array($lines2)) {
            $lines2 = explode("\n", $lines2);
        }
        foreach ($lines1 as $i => $line) {
            $lines1[$i] = rtrim($line, "\r\n");
        }
        foreach ($lines2 as $i => $line) {
            $lines2[$i] = rtrim($line, "\r\n");
        }

        $renderer = new DiffRendererHtmlInline();
        $diff = new \Diff($lines1, $lines2);

        return $diff->render($renderer);
    }
}
