<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ServiceFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Service';
    public $dataFile = '@common/fixtures/data/service.php';
}