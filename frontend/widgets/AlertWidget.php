<?php
namespace frontend\widgets;

use Yii;
use common\models\Alert;

class AlertWidget extends \yii\base\Widget
{
	public $page;

    public function run()
    {
        $model = Alert::findOne(['id_page'=>$this->page->id_page]);

        if (empty($model) || $model->state == 0)
            return '';

        return $this->render('alert',[
        	'model'=>$model,
        ]);
    }
}