<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class ServiceSituationFixture extends ActiveFixture
{
    public $modelClass = 'common\models\ServiceSituation';
    public $dataFile = '@common/fixtures/data/service_situation.php';
}