<?php
namespace frontend\widgets;

use Yii;
use common\models\Form;

class FormWidget extends \yii\base\Widget
{
	public $id_form;
	public $form;
	public $template = 'form';

    public function run()
    {
    	if (empty($this->form) && !empty($this->id_form))
        	$this->form = Form::findOne($this->id_form);

        if (empty($this->form))
        	return false;

        return $this->render('form/'.$this->template,[
        	'form'=>$this->form,
        ]);
    }
}
