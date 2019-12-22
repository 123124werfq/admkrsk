<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;

class CollectionRecordWidget extends \yii\base\Widget
{
    public $collectionRecord;
    public $renderTemplate = false;
    public $templateAsElement = false;

    public function run()
    {
    	$template = '';

    	if ($this->renderTemplate)
    	{
    		if ($this->templateAsElement)
    			$template = $this->collectionRecord->collection->template_element;
    		else
    			$template = $this->collectionRecord->collection->template;

        	$columns = $this->collectionRecord->collection->getColumns()->indexBy('alias')->all();
    	}
        else
        	$columns = $this->collectionRecord->collection->columns;

        return $this->render($this->renderTemplate?'collection/_template':'collection/_record',[
        	'template'=>$template,
        	'Record'=>$this->collectionRecord->getData($this->renderTemplate),
            'columns'=>$columns,
        ]);
    }
}
