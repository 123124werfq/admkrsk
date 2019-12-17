<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;

class CollectionRecordWidget extends \yii\base\Widget
{
    public $collectionRecord;

    public function run()
    {
        $columns = $this->collectionRecord->collection->columns;

        return $this->render('collection/_record',[
        	'Record'=>$this->collectionRecord->getData(),
            'columns'=>$columns,
        ]);
    }
}
