<?php

namespace common\traits;

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @property string $verboseName
 * @property string $verboseNamePlural
 * @property string $label
 * @property string $labelPlural
 */
trait MetaTrait
{
    public $verboseName;
    public $verboseNamePlural;

    public function getLabel()
    {
        return $this->verboseName ?: Inflector::humanize(StringHelper::basename(self::class));
    }

    public function getLabelPlural()
    {
        return $this->verboseNamePlural ?: Inflector::pluralize(StringHelper::basename(self::class));
    }
}