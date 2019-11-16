<?php
	use yii\helpers\Html;
	use common\models\CollectionColumn;

	$options = json_decode($input->options,true);

	$options['class'] = 'form-control';

	if (!empty($input->required))
		$options['required'] = true;

	$options['id'] = "input".$input->id_input;

	$groupClass = '';

	if ($input->type->type==CollectionColumn::TYPE_CHECKBOXLIST)
		$groupClass .= ' checkboxlist';

	if ($input->type->type==CollectionColumn::TYPE_SELECT)
		$groupClass .= ' custom-select';

	$visibleField = '';

	if (!empty($input->visibleInput))
		$visibleField = 'data-visible-field="input'.$input->visibleInput.'"';

	$visibleInputValues = '';

	if (!empty($input->visibleInputValues))
		$visibleInputValues = 'data-values="input'.implode(',', $input->visibleInputValues).'"';
?>

<div class="col">
	<div class="form-group <?=$groupClass?>" <?=$visibleField?> <?=$visibleInputValues?>>
		<?php if (!empty($input->label)){?>
		<label class="form-label"><?=$input->label?><?=!empty($options['required'])?'*':''?></label>
		<?php }?>
		<?php switch ($input->type->type) {
			case CollectionColumn::TYPE_SELECT:
				echo $form->field($model, "input$input->id_input")->dropDownList($input->getArrayValues(),$options);
				break;

			case CollectionColumn::TYPE_DATE:
				$otions['type'] = 'date';
			case CollectionColumn::TYPE_INPUT:
				echo $form->field($model, "input$input->id_input")->textInput($options);
				break;
			case CollectionColumn::TYPE_TEXTAREA:
				echo $form->field($model, "input$input->id_input")->textArea($options);
				break;
			case CollectionColumn::TYPE_ADDRESS:
				echo $form->field($model, "input$input->id_input")->textInput($options);
$script = <<< JS
	$("#{$options['id']}").autocomplete({
        'minLength':'2',
        'showAnim':'fold',
        'select': function(event, ui) {

        },
        'change': function (event, ui) {
            if (ui.item)
            {
            }
            else {
                $("#{$options['id']}").val('');
            }
        },
        'source':'/search/address',
    })
    .data("autocomplete");
JS;
				$this->registerJs($script, yii\web\View::POS_END);
				break;
			case CollectionColumn::TYPE_FILE:
				echo'
				<div class="fileupload">
	                <div class="fileupload_dropzone">
	                    <div class="fileupload_btn">
	                        <span class="fileupload_btn-text">Выберите файлы</span>
	                        '.Html::fileInput("input$input->id_input",'',['class'=>'fileupload_control']).'
	                    </div>
	                    <div class="fileupload_content">
	                        <p class="fileupload_label">Перетащите сюда файлы для загрузки</p>
	                        <p class="text-help mt-0 mb-0">Максимальный размер файлов — 10 Мб</p>
	                    </div>
	                </div>
	                <div class="fileupload_list hidden"></div>
	            </div>';
				break;
			case CollectionColumn::TYPE_RADIO:
				foreach ($input->getArrayValues() as $key => $value) {
					echo '<div class="radio-group">
								<label class="radio">
									<input type="radio" name="input'.$input->id_input.'" value="'.Html::encode($value).'" class="radio_control">
									<span class="radio_label">'.$value.'</span>
								</label>
						  </div>';
				}
				break;
			case CollectionColumn::TYPE_CHECKBOX:
				echo '<div class="checkbox-group">
					<label class="checkbox checkbox__ib">
						<input type="checkbox" name="input'.$input->id_input.'" value="1" class="checkbox_control">
						<span class="checkbox_label">'.$input->name.'</span>
					</label>
				</div>';
				break;
			case CollectionColumn::TYPE_CHECKBOXLIST:
				foreach ($input->getArrayValues() as $key => $value) {
					echo '<div class="checkbox-group">
						<label class="checkbox checkbox__ib">
							<input type="checkbox" name="input'.$input->id_input.'[]" value="'.Html::encode($value).'" class="checkbox_control">
							<span class="checkbox_label">'.$value.'</span>
						</label>
					</div>';
				}
				break;
			default:
				break;
		}
	?>
		<?php if (!empty($input->hint)){?>
			<p class="text-help"><?=$input->hint?></p>
		<?php }?>
	</div>
</div>