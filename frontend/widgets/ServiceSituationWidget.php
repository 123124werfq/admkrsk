<?php
namespace frontend\widgets;

use Yii;
use common\models\ServiceSituation;
use common\models\Service;

class ServiceSituationWidget extends \yii\base\Widget
{
	public $bytype = false;
	public $page;
	public $block;

    public function run()
    {
        if (!empty($this->block))
        {
        	$blockVars = $this->block->getBlockVars()->indexBy('alias')->all();

        	if (!empty($blockVars['bytype']->value))
        		$this->bytype = $blockVars['bytype']->value;
        }

    	if ($this->bytype)
    	{
    		$sql = "SELECT id_situation FROM service_service ss
                        LEFT JOIN servicel_situation sls ON ss.id_service = sls.id_service
                            WHERE client_type&".Service::TYPE_PEOPLE.'='.Service::TYPE_PEOPLE;
    		$ids = Yii::$app->db->createCommand($sql)->queryColumn();

    		$situations = ServiceSituation::find()->joinWith('childs as childs')->where('service_situation.id_parent IS NULL')->andWhere(['childs.id_situation'=>$ids])->all();

            $sql = "SELECT id_situation FROM service_service ss
                        LEFT JOIN servicel_situation sls ON ss.id_service = sls.id_service
                            WHERE client_type&".Service::TYPE_FIRM.'='.Service::TYPE_FIRM;
            $ids = Yii::$app->db->createCommand($sql)->queryColumn();

            $firmsituations = ServiceSituation::find()->joinWith('childs as childs')->where('service_situation.id_parent IS NULL')->andWhere(['childs.id_situation'=>$ids])->all();

    		return $this->render($this->bytype?'service/service_situation_bytype':'menu/situations',[
	        	'situations'=>$situations,
	        	'firmsituations'=>$firmsituations,
	        ]);

    	}

        $situations = ServiceSituation::find()->with('childs')->where('id_parent IS NULL')->all();

        return $this->render($this->bytype?'service/service_situation_bytype':'menu/situations',[
        	'situations'=>$situations,
        ]);
    }
}