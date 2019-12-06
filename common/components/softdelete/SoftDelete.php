<?php

namespace common\components\softdelete;

class SoftDelete
{
    const EVENT_BEFORE_SOFT_DELETE = 'beforeSoftDelete';
    const EVENT_AFTER_SOFT_DELETE = 'afterSoftDelete';
    const EVENT_BEFORE_FORCE_DELETE = 'beforeForceDelete';
    const EVENT_AFTER_FORCE_DELETE = 'beforeForceDelete';
    const EVENT_BEFORE_RESTORE = 'beforeRestore';
    const EVENT_AFTER_RESTORE = 'afterRestore';
}