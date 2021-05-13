<?php

namespace frontend\widgets;

use common\models\HrReserve;
use yii\base\Widget;

class HrreserveWidget extends Widget
{
    public $page;
    public $attributes;
    public $objectData; // данные CollectionRecord объекста если идет его рендер

    public function run()
    {
        $reservers = HrReserve::find()->joinWith(['profile as profile']);

        if (!empty($_GET['id_record_position']))
            $reservers->andWhere(['id_record_position'=>(int)$_GET['id_record_position']]);

        $output = [];
        $groups = [];

        foreach ($reservers->all() as $reserver)
        {
            $fio = $reserver->profile->name;

            if (!empty($_GET['fio']))
                if (mb_stripos($fio, $_GET['fio'])===false)
                    continue;

            $posname = $reserver->getPositionName();
            $posdate = $reserver->contest_date;

            if (!isset($output[$reserver->id_profile]))
                $output[$reserver->id_profile] = [];

            $groups[$reserver->id_record_position] = $posname;

            $output[$reserver->id_profile][] = [
                'name' => $fio,
                'position' => $posname,
                'date' => $posdate
            ];
        }

        ksort($output);

        return $this->render('reserve',[
            'reservers' => $output,
            'groups'=>$groups,
        ]);
    }
}
