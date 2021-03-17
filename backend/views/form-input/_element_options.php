<?php
	use yii\helpers\Html;
	use common\models\FormInput;
	use common\models\CollectionColumn;
	use yii\helpers\ArrayHelper;

	$data = $element->getOptionsData();

	$visibleInputs = [];

	$inputs = FormInput::find()->where([
				'id_form'=>$id_form,
				'type'=>[CollectionColumn::TYPE_SELECT,
						 CollectionColumn::TYPE_CHECKBOX,
						 CollectionColumn::TYPE_RADIO,
						 CollectionColumn::TYPE_COLLECTION,
						 CollectionColumn::TYPE_SERVICETARGET]
			  ]);

	if (!empty($input))
		$inputs->andWhere('id_input <> '.(int)$input->id_input);

    $visibleInputs = ArrayHelper::map($inputs->all(), 'id_input', 'name');

	echo '<div class="row-flex">';
	foreach ($data as $key => $option)
	{
		$option['class'] = 'form-control';
		echo '<div class="col">
				<label class="control-label">'.$option['name'].'</label>';
				echo Html::textInput("FormElement[options][$key]",$option['value'],$option);
		echo '</div>';
	}
	echo '</div>';

	if (!empty($visibleInputs)){
        $records = $element->getRecords('visibleInputs');
    ?>
        <p>Отображать если:</p>
        <div id="visibles" class="multiyiinput">
        <?php foreach ($records as $key => $visibleInput) {?>
            <div class="row" data-row="<?=$key?>">
                <div class="col-sm-5">
                <?=$form->field($visibleInput, "[visibleInputs][$key]id",['template'=>"{input}"])->hiddenInput()?>
                    <?=$form->field($visibleInput, "[visibleInputs][$key]id_input_visible",['template'=>"{input}"])->dropDownList($visibleInputs,['class'=>'form-control visible-field','prompt'=>'Выберите поле зависимости'])?>
                </div>
                <div class="col-sm-6 visibleInputValues">
                    <?=(!empty($visibleInput->visibleInput))?$this->render('_input',['visibleInput'=>$visibleInput->visibleInput,'model'=>$visibleInput,'form'=>$form,'rowKey'=>$key]):''?>
                </div>
                <div class="col-sm-1">
                    <a class="close" href="javascript:">&times;</a>
                </div>
            </div>
        <?php
            }
        ?>
        </div>
        <a onclick="return addInput('visibles')" href="#" class="btn btn-default btn-visible">Добавить еще</a>
<?php
    }
?>