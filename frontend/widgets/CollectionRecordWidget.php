<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;

class CollectionRecordWidget extends \yii\base\Widget
{
    public $collectionRecord;
    public $renderTemplate = false;
    public $templateAsElement = false;
    public $columnsAlias = [];
    public $columns = [];
    public $noRecursion = false; // deprecated
    public $recursionCollections = [];

    public function run()
    {
    	$template = '';

    	if ($this->renderTemplate)
    	{
    		if ($this->templateAsElement)
    			$template = $this->collectionRecord->collection->template_element;
    		else
    			$template = $this->collectionRecord->collection->template;

            if (empty($this->columns))
        	   $columns = $this->collectionRecord->collection->getColumns()->indexBy('alias')->all();
    	}
        else
        {
            if (empty($this->columns))
            {
                if (!empty($this->columnsAlias))
                    $columns = $this->collectionRecord->collection->getColumns()->where(['alias'=>$this->columnsAlias])->all();
            	else
                    $columns = $this->collectionRecord->collection->columns;
            }
        }

        if (!empty($this->columns))
            $columns = $this->columns;

        $recorData = $this->collectionRecord->getDataRaw($this->renderTemplate,true);

        return $this->render($this->renderTemplate?'collection/_template':'collection/_record',[
        	'template'=>$template,
        	'recordData'=>$recorData,
            'columns'=>$columns,
            'recursionCollections'=>$this->recursionCollections,
            'noRecursion'=>$this->noRecursion,
        ]);
    }
}
