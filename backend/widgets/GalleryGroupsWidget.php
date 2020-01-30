<?php

namespace backend\widgets;

use common\models\Gallery;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @property Gallery $model
 */
class GalleryGroupsWidget extends Select2
{
    public function init()
    {
        parent::init();

        $this->options['multiple'] = true;

        $this->pluginOptions = [
            'allowClear' => true,
            'minimumInputLength' => 0,
            'ajax' => [
                'url' => Url::toRoute(['/gallery-group/list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
        ];

        $groups = $this->model->groups;

        $data = [];
        foreach ($groups as $galleryGroup) {
            $data[] = [
                'id' => $galleryGroup->id,
                'name' => $galleryGroup->name
            ];
        }

        $this->model->{$this->attribute} = ArrayHelper::getColumn($data, 'id');
        $this->data = ArrayHelper::map($data, 'id', 'name');
    }
}
