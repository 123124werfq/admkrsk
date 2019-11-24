<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ServiceTargetFixture extends ActiveFixture
{
    public $modelClass = 'common\models\ServiceTarget';
    public $dataFile = '@common/fixtures/data/service_target.php';
}