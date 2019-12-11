<?php
namespace frontend\widgets;

use Yii;
use common\models\Form;
use common\models\CollectionRecord;
use common\models\FormDynamic;

class FormsWidget extends \yii\base\Widget
{
    public $attributes; // атрибуты приходят из текстового редактора

	public $id_form = null; // id моделя формы
	public $form = null; // модель формы
	public $template = 'form'; // шаблон
    public $inputs = []; // скрытые инпуты которые нужно передать в POST
    public $action = null; // куда направляеть форму, если пусто то пойдут в унивартсальный контролер
    public $data = null; // данные
    public $collectionRecord = null; // данные
    public $arrayGroup = null; // группирующий признак для подколлекций
    public $activeForm = null; // класс эктив форм для подколлекций
    
    public $page;

    public function run()
    {
    	if (empty($this->form) && !empty($this->id_form))
        	$this->form = Form::findOne($this->id_form);

        if (!empty($this->attributes['id']))
            $this->form = Form::findOne($this->attributes['id']);

        if (empty($this->form))
        	return false;

        $model = new FormDynamic($this->form,$this->data);

        if (!empty($this->collectionRecord) && empty($this->data))
            $model->loadDataFromRecord($this->collectionRecord->getData());

        return $this->render('form/'.$this->template,[
        	'form'=>$this->form,
            'model'=>$model,
            'inputs'=>$this->inputs,
            'action'=>$this->action,
            
            'arrayGroup'=>$this->arrayGroup,
            'activeForm'=>$this->activeForm,
        ]);
    }
}
