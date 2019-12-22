<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;

class CollectionRecordWidget extends \yii\base\Widget
{
    public $collectionRecord;
    public $renderTemplate = 0;

    public function run()
    {
        $columns = $this->collectionRecord->collection->columns;

        return $this->render(($this->renderTemplate && !empty($this->collectionRecord->collection->template))?'collection/_template':'collection/_record',[
        	'template'=>$this->renderTemplate?$this->collectionRecord->collection->template:'',
        	'Record'=>$this->collectionRecord->getData($this->renderTemplate),
            'columns'=>$columns,
        ]);
    }
}
