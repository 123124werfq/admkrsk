<?php

namespace common\traits;

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @property string $verboseName
 * @property string $verboseNamePlural
 * @property string $titleAttribute
 * @property string $breadcrumbsLabel
 * @property string $pageTitle
 */
trait MetaTrait
{
    public function getBreadcrumbsLabel()
    {
        return defined('self::VERBOSE_NAME_PLURAL') ? self::VERBOSE_NAME_PLURAL : Inflector::pluralize(StringHelper::basename(self::class));
    }

    public function getPageTitle()
    {
        return defined('self::TITLE_ATTRIBUTE') ? $this->{self::TITLE_ATTRIBUTE} : (defined('self::VERBOSE_NAME') ? self::VERBOSE_NAME : StringHelper::basename(self::class)) . ' #' . $this->primaryKey;
    }
}
