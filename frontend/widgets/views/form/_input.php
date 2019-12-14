<?php
	use yii\helpers\Html;
	use common\models\CollectionColumn;
	use kartik\select2\Select2;
	use yii\web\JsExpression;

	$styles = $element->getStyles();

	$options = $input->options;
	$clear = [];

	if (!empty($options))
		foreach ($options as $key => $value)
			if (!empty($value))
				$clear[$key] = $value;

	$options = $clear;

	$options['class'] = 'form-control';

	if (!empty($input->required))
		$options['required'] = true;

	if (!empty($input->readonly))
		$options['readonly'] = true;

	if(empty($options['id']))
		$options['id'] = "input".$input->id_input;

	$groupClass = '';

	if ($input->type==CollectionColumn::TYPE_CHECKBOXLIST)
		$groupClass .= ' checkboxlist';

	if ($input->type==CollectionColumn::TYPE_SELECT)
		$groupClass .= ' custom-select';

	$attribute = "input$input->id_input";

	if (!empty($arrayGroup))
		$attribute = "[$arrayGroup]".$attribute;


	$styles = $element->getStyles();

	$id_subform = (!empty($subform))?$subform->id_form:'';

?>

<div id="element<?=$element->id_element?>" class="col">
	<div id="inputGroup<?=$input->id_input?>" <?=(!empty($styles))?'style="'.implode(';',$styles).'"':''?> class="form-group <?=$groupClass?>">
		<?php if (!empty($input->label) && $input->type !=CollectionColumn::TYPE_CHECKBOX){?>
		<label class="form-label"><?=$input->label?><?=!empty($options['required'])?'*':''?></label>
		<?php }?>
		<?php switch ($input->type) {
			case CollectionColumn::TYPE_SERVICETARGET:
				echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(),$options);
				break;
			case CollectionColumn::TYPE_SELECT:
				echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(),$options);
				break;
			case CollectionColumn::TYPE_DATE:
				$options['type'] = 'date';
				if (strpos($attribute, ']')>0)
					$clearAttribute = substr($attribute, strpos($attribute, ']')+1);
				else
					$clearAttribute = $attribute;

				if (is_numeric($model->$clearAttribute))
					$model->$clearAttribute = date('Y-m-d', $model->$clearAttribute);

				echo $form->field($model, $attribute)->textInput($options);
				break;
			case CollectionColumn::TYPE_DATETIME:

				if (strpos($attribute, ']')>0)
					$clearAttribute = substr($attribute, strpos($attribute, ']')+1);
				else
					$clearAttribute = $attribute;
				if (is_numeric($model->$clearAttribute))
					$model->$clearAttribute = date('Y-m-d\TH:i:s', $model->$clearAttribute);

				$options['type'] = 'datetime-local';
				echo $form->field($model, $attribute)->textInput($options);
				break;
			case CollectionColumn::TYPE_INTEGER:
				$options['type'] = 'number';
				echo $form->field($model, $attribute)->textInput($options);
				break;
			case CollectionColumn::TYPE_INPUT:
				echo $form->field($model, $attribute)->textInput($options);
				break;
			case CollectionColumn::TYPE_TEXTAREA:
				echo $form->field($model, $attribute)->textArea($options);
				break;
			case CollectionColumn::TYPE_ADDRESS:
				echo $form->field($model, $attribute)->textInput($options);
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

				$dataOptions = [];

				if (!empty($options['acceptedFiles']))
					$dataOptions[] = 'data-acceptedfiles="'.$options['acceptedFiles'].'"';

				if (!empty($options['maxFiles']))
					$dataOptions[] = 'data-maxfiles="'.$options['maxFiles'].'"';

				echo'
				<div data-input="'.$input->id_input.'" class="fileupload" '.implode(' ', $dataOptions).'>
	                <div class="fileupload_dropzone">
	                    <div class="fileupload_btn">
	                        <span class="fileupload_btn-text">Выберите файлы</span>
	                        <div class="fileupload_control"></div>
	                    </div>
	                    <div class="fileupload_content">
	                        <p class="fileupload_label">Перетащите сюда файлы для загрузки</p>
	                        <p class="text-help mt-0 mb-0">Максимальный размер файлов — '.(!empty($options['filesize'])?$options['filesize']:'10').' Мб</p>
	                    </div>
	                </div>
	                <div class="fileupload_list"></div>
	            </div>';
				break;
			case CollectionColumn::TYPE_IMAGE:
				$dataOptions = [];

				if (!empty($options['acceptedFiles']))
					$dataOptions[] = 'data-acceptedFiles="'.$options['acceptedFiles'].'"';

				if (!empty($options['maxFiles']))
					$dataOptions[] = 'data-maxFiles="'.$options['maxFiles'].'"';

				echo'
				<div data-input="'.$input->id_input.'" class="fileupload" '.implode(' ', $dataOptions).' >
	                <div class="fileupload_dropzone ">
	                    <div class="fileupload_btn">
	                        <span class="fileupload_btn-text">Выберите файлы</span>
	                        <div class="fileupload_control"></div>
	                    </div>
	                    <div class="fileupload_content">
	                        <p class="fileupload_label">Перетащите сюда файлы для загрузки</p>
	                        <p class="text-help mt-0 mb-0">Максимальный размер файлов — '.(!empty($options['filesize'])?$options['filesize']:'10').' Мб</p>
	                    </div>
	                </div>
	                <div class="fileupload_list"></div>
	            </div>';
				break;
			case CollectionColumn::TYPE_RADIO:
				foreach ($input->getArrayValues() as $key => $value) {
					echo '<div class="radio-group">
								<label class="radio">
									<input type="radio" name="FormDynamic['.$attribute.']" value="'.Html::encode($key).'" class="radio_control">
									<span class="radio_label">'.$value.'</span>
								</label>
						  </div>';
				}
				break;
			case CollectionColumn::TYPE_CHECKBOX:

				if (!empty($options['popup']))
					$options['data-modal'] = 'input'.$input->id_input.'Modal';

				if (!empty($options['popup']))
				{
					echo '<div id="input'.$input->id_input.'Modal" style="display: none; max-width: 1000px;">
								'.$options['terms'].'
						        <p>
						        	<center>
							        	<button data-id="input'.$input->id_input.'" class="btn btn__secondary accept-checkbox">Ознакомлен</button>
							            <button data-fancybox-close="" class="btn btn-primary">Закрыть</button>
						            <center>
						        </p>
						   </div>';
				}

				$options['class'] = 'checkbox_control'.(!empty($options['popup'])?' modal-checkbox':'');
				$options['value'] = (!empty($input->values)?Html::encode($input->values):1);

				unset($options['popup']);
				unset($options['terms']);

				echo '<div class="checkbox-group">
					<label class="checkbox checkbox__ib">
						'.Html::checkBox('FormDynamic['.$attribute.']',!empty($model->$attribute),$options).'
						<span class="checkbox_label">'.($input->label??$input->name).'</span>
					</label>
				</div>';
				break;
			case CollectionColumn::TYPE_CHECKBOXLIST:
				echo '<div class="checkboxes">';
				foreach ($input->getArrayValues() as $key => $value) {
					echo '
					<div class="checkbox-group">
						<label class="checkbox checkbox__ib">
							<input type="checkbox" name="FormDynamic['.$attribute.'][]" value="'.Html::encode($key).'" class="checkbox_control">
							<span class="checkbox_label">'.$value.'</span>
						</label>
					</div>';
				}
				echo '</div>';
				break;
			case CollectionColumn::TYPE_COLLECTION:

				$value = [];

				if (!empty($model->$attribute))
				{
					$record = \common\models\Collection::findOne($model->$attribute);
					if (!empty($record))
						$value = [$record->id_collection=>$record->name];
				}

				echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => $value,
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Выберите запись',
                        'ajax' => [
                            'url' => '/collection/list',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:$("#input-city'.$id_subform.'").val()};}')
                        ],
                    ],
                    'options'=>[
                    	'id'=>'input-district'.$id_subform
                    ]
                ]);
				break;

			case CollectionColumn::TYPE_COLLECTIONS:

				if (!empty($options['accept_add']))
				{
					$arrayGroup = md5(rand(0,10000).time());
					echo '<div id="subforms'.$input->id_input.'">';
					echo \frontend\widgets\FormsWidget::widget([
						'form'=>$input->collection->form,
						'arrayGroup'=>$arrayGroup,
						'activeForm'=>$form,
						'inputs'=>[$attribute.'[]'=>$arrayGroup],
						'template'=>'form_in_form',
					]);
					echo '</div>';

					echo '<div class="collections-action-buttons"><a data-id="'.$input->id_input.'" data-group="subforms'.$input->id_input.'" class="btn btn__secondary form-copy" href="javascript:">Добавить еще</a></div>';
				}
				else
				{
					$value = [];

					if (!empty($model->$attribute))
					{
						$records = json_decode($model->$attribute);
						$records = \common\models\CollectionRecord::find()->where(['id_record'=>$records]);

						if (!empty($records))
						{
							$value[] = [$record->id_record=>$record->getLabel()];
						}
					}

					echo $form->field($model, $attribute)->widget(Select2::class, [
	                    'data' => $value,
	                    'pluginOptions' => [
	                        'multiple' => true,
	                        //'allowClear' => true,
	                        'minimumInputLength' => 0,
	                        'placeholder' => 'Выберите записи',
	                        'ajax' => [
	                            'url' => '/collection/record-list',
	                            'dataType' => 'json',
	                            'data' => new JsExpression('function(params) { return {search:params.term,id:'.$input->id_collection.'};}')
	                        ],
	                    ],
	                    'options'=>[
	                    	'id'=>'input-district',
	                    	'multiple' => true,
	                    ]
	                ]);
				}
				break;
			case CollectionColumn::TYPE_DISTRICT:

				$value = [];

				if (!empty($model->$attribute))
				{
					$district = \common\models\District::findOne($model->$attribute);
					if (!empty($district))
						$value = [$district->id_district=>$district->name];
				}

				echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => $value,
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Район',
                        'ajax' => [
                            'url' => '/address/district',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:$("#input-city'.$id_subform.'").val()};}')
                        ],
                    ],
                    'options'=>[
                    	'id'=>'input-district'.$id_subform
                    ]
                ]);
				break;
			case CollectionColumn::TYPE_REGION:
				echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => [],
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Регион',
                        'ajax' => [
                            'url' => '/address/region',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term};}')
                        ],
                    ],
                    'options'=>[
                    	'id'=>'input-region'.$id_subform
                    ]
                ]);
				break;
			case CollectionColumn::TYPE_SUBREGION:
				echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => [],
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Область',
                        'ajax' => [
                            'url' => '/address/subregion',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_region:$("#input-region'.$id_subform.'").val()};}')
                        ],
                    ],
                    'options'=>[
                    	'id'=>'input-subregion'.$id_subform
                    ]
                ]);
				break;
			case CollectionColumn::TYPE_CITY:
				echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => [],
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Город',
                        'ajax' => [
                            'url' => '/address/city',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_region:$("#input-region'.$id_subform.'").val(),id_subregion:$("#input-subregion'.$id_subform.'").val()};}')
                        ],
                    ],
                    'options'=>[
                    	'id'=>'input-city'.$id_subform
                    ]
                ]);
				break;
			case CollectionColumn::TYPE_STREET:
				echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => [],
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Улица',
                        'ajax' => [
                            'url' => '/address/street',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:$("#input-city'.$id_subform.'").val(),id_district:$("#input-district'.$id_subform.'").val()};}')
                        ],
                    ],
                    'options'=>[
                    	'id'=>'input-street'.$id_subform
                    ]
                ]);
				break;
			case CollectionColumn::TYPE_HOUSE:
				echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => [],
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Дом',
                        'ajax' => [
                            'url' => '/address/house',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_street:$("#input-street'.$id_subform.'").val()};}')
                        ],
                    ],
                    'pluginEvents'=>[
                    	"select2:select" => "function(e) {
                    		if ($('#postalcode".$id_subform."').length>0)
                    			$('#postalcode".$id_subform."').val(e.params.data.postalcode);
                    	}",
                    ]
                ]);
				break;
			case CollectionColumn::TYPE_JSON:

				$columns = json_decode($input->values,true);

				if (is_string($columns))
					$columns = json_decode($columns,true);

				$data = json_decode($model->$attribute);

				if (!is_array($columns)) {
                    $columns = [];
                }
?>
				<table class="form-table">
					<thead>
						<tr>
						<?php foreach ($columns as $key => $column) {
							echo '<th '.(!empty($column['width'])?'style="width:'.$column['width'].'%"':'').' >'.$column['name'].'</th>';
						}?>
						<th></th>
						</tr>
					</thead>
					<tbody id="inputs<?=$input->id_input?>">
						<tr>
							<?php
								if (empty($data)){
									$i = 0;
									foreach ($columns as $key => $column) {
										echo '<td><input id="input'.$input->id_input.'_col'.$i.'" type="'.($column['type']??'text').'" name="FormDynamic[input'.$input->id_input.'][0]['.$i.']" class="form-control"/></td>';
										$i++;
									}
								}
								else
								{
									foreach ($data as $key => $row)
									{
										$i = 0;
										foreach ($columns as $key => $column) {
											echo '<td><input id="input'.$input->id_input.'_col'.$i.'" type="'.($column['type']??'text').'" value="'.($row[$i]??'').'" name="FormDynamic[input'.$input->id_input.'][0]['.$i.']" class="form-control"/></td>';
											$i++;
										}
									}
								}
							?>
							<td width="10" class="td-close">
								<a class="close" onclick="return removeRow(this)" href="javascript:">&times;</a>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<td colspan="<?=count($columns)+1?>">
							<a onclick="addInput('inputs<?=$input->id_input?>')" class="btn btn__gray" href="javascript:">Добавить</a>
						</td>
					</tfoot>
				</table>
<?php
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