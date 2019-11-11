<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ServiceRubricFixture extends ActiveFixture
{
    public $modelClass = 'common\models\ServiceRubric';
    public $dataFile = '@common/fixtures/data/service_rubric.php';
}