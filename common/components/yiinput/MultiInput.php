<?php

namespace common\components\yiinput;

use yii\base\Widget;

class MultiInput extends Widget
{
	public $model;
	public $relation_behavior;
    public $view = 'input';
    public $fields;
    public $single = false;
    public $button = 'Добавить еще';

    public function init()
    {
        // этот метод будет вызван методом CController::beginWidget()
    }

    public function run()
    {
    	$relation_behavior = $this->relation_behavior;
    	//$relation = $this->model->relation->models[$relation_behavior];
		$data = $this->model->getRecords($relation_behavior);

        $date = array();
        // находим поля с датами
        foreach ($this->fields as $key => $value) {
            if (!empty($value['params']['class']) && strpos($value['params']['class'],'datepicker'))
                $date[] = $key;
        }

        // преобразуем даты
        foreach ($data as $value) {
            foreach ($date as $field) {
                if ($value->$field)
                    $value->$field = date('d.m.Y',$value->$field);
            }
        }

    	return $this->render($this->view,[
            'data'=>$data,
            'fields'=>$this->fields,
            'relation'=>$relation_behavior,
            'single'=>$this->single,
            'button'=>$this->button
        ]);
    }
}