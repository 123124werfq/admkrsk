<?php

namespace frontend\widgets;

use common\models\HrReserve;
use yii\base\Widget;

class HrreserveWidget extends Widget
{
    public function run()
    {
        $reservers = HrReserve::findAll([]);

        return $this->render('reserve',[
            'reservers' => $reservers,
        ]);
    }
}
