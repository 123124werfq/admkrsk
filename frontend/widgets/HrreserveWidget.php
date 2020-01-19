<?php

namespace frontend\widgets;

use common\models\HrReserve;
use yii\base\Widget;

class HrreserveWidget extends Widget
{
    public $page;

    public function run()
    {
        $reservers = HrReserve::find()->all();

        $output = [];

        foreach ($reservers as $reserver)
        {
            $fio = $reserver->profile->name;
            $posname = $reserver->getPositionName();
            $posdate = $reserver->contest_date;

            if(!isset($output[$reserver->id_profile]))
                $output[$reserver->id_profile] = [];

            $output[$reserver->id_profile][] = [
                'name' => $fio,
                'position' => $posname,
                'date' => $posdate
            ];
        }

        ksort($output);

        return $this->render('reserve',[
            'reservers' => $output,
        ]);
    }
}
