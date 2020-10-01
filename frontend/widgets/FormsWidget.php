<?php
namespace frontend\widgets;

use Yii;
use common\models\Form;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
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
    public $nocaptcha=false;
    public $objectData; // данные CollectionRecord объекста если идет его рендер
    public $submitLabel = 'Отправить';

    public $page;

    public function run()
    {
    	if (empty($this->form) && !empty($this->id_form))
        	$this->form = Form::findOne($this->id_form);

        if (!empty($this->attributes['id']))
            $this->form = Form::findOne($this->attributes['id']);

        if (!empty($this->attributes['data']))
        {
            $this->data = json_decode($this->attributes['data'],true);
            $this->inputs['postData'] = json_encode($this->data);
        }

        if (!empty($this->objectData))
        {            
            $collectionRecord = CollectionRecord::findOne((int)$this->objectData['id_record']);

            $column = $this->form->collection->getColumns()
                ->where(['type'=>CollectionColumn::TYPE_COLLECTION])->one();
                //->joinWith('input as input')
                //->andWhere(['input.id_collection'=>$collectionRecord->id_collection])->one();

            if (!empty($column))
                $this->inputs['postData'] = json_encode([$column->alias => $collectionRecord->id_record]);
        }

        if (empty($this->form))
        	return false;

        if ($this->nocaptcha)
            $this->form->captcha = false;

        $model = new FormDynamic($this->form,$this->data);

        if (!empty($this->collectionRecord) && empty($this->data))
            $model->loadDataFromRecord($this->collectionRecord->getData());

        FormAssets::register($this->getView());

        return $this->render('form/'.$this->template,[
        	'form'=>$this->form,
            'model'=>$model,
            'inputs'=>$this->inputs,
            'data'=>$this->data,
            'action'=>$this->action,
            'arrayGroup'=>$this->arrayGroup,
            'activeForm'=>$this->activeForm,
            'submitLabel'=>$this->submitLabel,
        ]);
    }
}
