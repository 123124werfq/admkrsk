<?php

use backend\widgets\MapInputWidget;
use common\models\District;
use common\models\FormElement;
use common\models\Media;
use common\models\City;
use yii\helpers\Html;
use common\models\CollectionColumn;
use common\models\CollectionRecord;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

/** @var FormElement $element */
$styles = $element->getStyles();

$options = $input->options;
$clear = [];

if (!empty($options))
    foreach ($options as $key => $value)
        if (!empty($value))
            $clear[$key] = $value;

$options = $clear;

$options['class'] = 'form-control';

if (!empty($input->required) && $input->type == CollectionColumn::TYPE_CHECKBOX)
    $options['required'] = true;

if (!empty($input->readonly) && strpos(Yii::$app->params['backendUrl'],'/'.$_SERVER['SERVER_NAME'])===false)
{
    $options['readonly'] = true;

    // for checkbox
    if ($input->type == CollectionColumn::TYPE_CHECKBOX)
        $options['disabled'] = true;
}

$groupClass = '';

if ($input->type == CollectionColumn::TYPE_CHECKBOXLIST)
    $groupClass .= ' checkboxlist';

if ($input->type == CollectionColumn::TYPE_SELECT)
    $groupClass .= ' custom-select';

$clearAttribute = $attribute = "input$input->id_input";

if (!empty($arrayGroup))
{
    $inputname = "FormDynamic[$arrayGroup][$attribute]";
    $attribute = "[$arrayGroup]" . $attribute;
    $id_input = str_replace(['[',']'], '_', $attribute);

    if (empty($options['id']))
        $options['id'] = "formdynamic-".$arrayGroup.'-'.$clearAttribute;
}
else
{
    $id_input = $attribute;
    $inputname = "FormDynamic[$attribute]";

    if (empty($options['id']))
        $options['id'] = "formdynamic-form".$modelForm->id_form.'-'.$clearAttribute;
}

$id_subform = (!empty($subform)) ? $subform->id_form : '';

if (empty($modelForm->maxfilesize))
    $modelForm->maxfilesize = 10;
?>

<div id="element<?= $element->id_element ?>" class="col" <?= (!empty($styles)) ? 'style="' . implode(';', $styles) . '"' : '' ?>>
    <div id="inputGroup<?= $input->id_input ?>" class="form-group <?=$groupClass?> <?=$options['id']?>">
        <?php if (!empty($input->label) && $input->type != CollectionColumn::TYPE_CHECKBOX) { ?>
            <label class="form-label"><?= $input->label ?><?=!empty($input->required)?' <span class="red">*</span>' : '' ?></label>
        <?php } ?>
        <?php if (!empty($input->copyInput)){?>
            <div class="checkbox-group">
                <label class="checkbox checkbox__ib">
                    <input class="checkbox_control copydate" type="checkbox" data-input="<?=$input->copyInput->id_input?>" name="copydate" value="<?=$input->id_input?>"/>
                    <span class="checkbox_label">Совпадает с <?=$input->copyInput->label?$input->copyInput->label:$input->copyInput->name?></span>
                </label>
            </div>
        <?php }?>
        <?php switch ($input->type) {
            case CollectionColumn::TYPE_SERVICETARGET:
                echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(), $options);
                break;
            case CollectionColumn::TYPE_SERVICE:
                echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(), $options);
                break;
            case CollectionColumn::TYPE_SERVICES:
                echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => $input->getArrayValues(),
                    'pluginOptions' => [
                        'multiple' => true,
                        'placeholder' => 'Выберите услуги',
                    ],
                    'options' => [
                        'multiple' => true,
                        //'value' => array_keys($value)
                    ]
                ]);
                /*echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(), $options);*/
                break;
            case CollectionColumn::TYPE_SELECT:
                $options['prompt'] = 'Выберите значение';
                echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(), $options);
                break;
            case CollectionColumn::TYPE_MAP:
                echo MapInputWidget::widget(['name' => $inputname, 'index' => $options['id'], 'value' => $model->$clearAttribute]);
                break;
            case CollectionColumn::TYPE_DATE:
                $options['type'] = 'date';

                if (!is_numeric($model->$clearAttribute) && (!empty($model->$clearAttribute)))
                    $model->$clearAttribute = strtotime($model->$clearAttribute);

                if (!empty($model->$clearAttribute))
                    $model->$clearAttribute = date('Y-m-d', $model->$clearAttribute);
                else if (!empty($options['default']))
                    $model->$clearAttribute = date('Y-m-d');

                echo $form->field($model, $attribute)->textInput($options);
                break;
            case CollectionColumn::TYPE_DATETIME:

                if (!is_numeric($model->$clearAttribute) && (!empty($model->$clearAttribute)))
                    $model->$clearAttribute = strtotime($model->$clearAttribute);

                if (!empty($model->$clearAttribute))
                    $model->$clearAttribute = date('Y-m-d\TH:i', $model->$clearAttribute);
                else if (!empty($options['default']))
                    $model->$clearAttribute = date('Y-m-d\TH:i');

                $options['type'] = 'datetime-local';
                echo $form->field($model, $attribute)->textInput($options);
                break;
            case CollectionColumn::TYPE_INTEGER:
                $options['type'] = 'number';
                if (empty($options['step']))
                    $options['step'] = 'any';
                echo $form->field($model, $attribute)->textInput($options);
                break;
            case CollectionColumn::TYPE_INPUT:

                if (!empty($options['type']) && $options['type']=='date')
                {
                    if (!is_numeric($model->$clearAttribute) && (!empty($model->$clearAttribute)))
                        $model->$clearAttribute = strtotime($model->$clearAttribute);

                    if (!empty($model->$clearAttribute))
                        $model->$clearAttribute = date('Y-m-d', $model->$clearAttribute);
                }

                echo $form->field($model, $attribute)->textInput($options);
                break;
            case CollectionColumn::TYPE_TEXTAREA:
                echo $form->field($model, $attribute)->textArea($options);
                break;
            case CollectionColumn::TYPE_RICHTEXT:
                $options['class'] .= ' redactor';
                echo $form->field($model, $attribute)->textArea($options);
                break;
            case CollectionColumn::TYPE_REPEAT:
                echo $this->render('inputs/_repeat',[
                    'model'=>$model,
                    'id_input'=>$id_input,
                    'input'=>$input,
                    'form'=>$form,
                    'options'=>$options,
                    'attribute'=>$attribute,
                    'inputname'=>$inputname,
                    'clearAttribute'=>$clearAttribute,
                ]);
                break;
            case CollectionColumn::TYPE_ADDRESSES:
                echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => [],
                    'pluginOptions' => [
                        'multiple' => true,
                        'minimumInputLength' => 1,
                        'placeholder' => 'Выберите адрес',
                        'ajax' => [
                            'url' => '/search/address',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term};}')
                        ],
                    ],
                    'options' => [
                        'multiple' => true,
                        'value' => array_keys($value)
                    ]
                ]);
                break;
            case CollectionColumn::TYPE_ADDRESS:

                if (empty($model->$clearAttribute))
                {
                    $value = [
                        'country'=>'',
                        'id_country'=>'',
                        'region'=>'',
                        'id_region'=>'',
                        'subregion'=>'',
                        'id_subregion'=>'',
                        'city'=>'',
                        'id_city'=>'',
                        'district'=>'',
                        'id_district'=>'',
                        'street'=>'',
                        'id_street'=>'',
                        'house'=>'',
                        'id_house'=>'',
                        'houseguid'=>'',
                        'lat'=>'',
                        'lon'=>'',
                        'postalcode'=>'',
                        'place'=>'',
                        'id_place'=>'',
                    ];

                    $city = City::find()->where("name LIKE '%Красноярск%'")->one();

                    if (!empty($city))
                    {
                        $value['id_city'] = $city->id_city;
                        $value['city'] = $city->name;

                        $house = $city->getHouses()->one();

                        $value['country'] = $house->country->name??'';
                        $value['id_country'] = $house->country->id_country??'';

                        $value['region'] = $house->region->name??'';
                        $value['id_region'] = $house->region->id_region??'';
                    }

                    $model->$clearAttribute = $value;
                }
                else
                    $value = $model->$clearAttribute;

                echo '<div class="flex-wrap">';

                if (!empty($options['show_country']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[country]')->widget(Select2::class, [
                    'data' => [$value['id_country']?:$value['country']=>$value['country']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'tags' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Страна',
                        'ajax' => [
                            'url' => '/address/country',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_country'])?$value['country']:$value['id_country'],
                        'id' => 'input-country' . $id_input
                    ]
                ]).'</div>';

                if (!empty($options['show_region']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[region]')->widget(Select2::class, [
                    'data' => [$value['id_region']?:$value['region']=>$value['region']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'minimumInputLength' => 0,
                        'tags'=> true,
                        'placeholder' => 'Регион',
                        'ajax' => [
                            'url' => '/address/region',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_country:getValueById("input-country' . $id_input . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_region'])?$value['region']:$value['id_region'],
                        'id' => 'input-region' . $id_input
                    ]
                ]).'</div>';

                if (!empty($options['show_subregion']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[subregion]', ['enableClientValidation' => false])->widget(Select2::class, [
                    'data' => [$value['id_subregion']?:$value['subregion']=>$value['subregion']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'minimumInputLength' => 0,
                        'tags'=> true,
                        'placeholder' => 'Область / Район',
                        'ajax' => [
                            'url' => '/address/subregion',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_region:getValueById("input-region' . $id_input . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_subregion'])?$value['subregion']:$value['id_subregion'],
                        'id' => 'input-subregion' . $id_input
                    ]
                ]).'</div>';

                if (!empty($options['show_city']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[city]')->widget(Select2::class, [
                    'data' => [$value['id_city']?:$value['city']=>$value['city']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'minimumInputLength' => 0,
                        'tags' => true,
                        'placeholder' => 'Город',
                        'ajax' => [
                            'url' => '/address/city',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_region:getValueById("input-region' . $id_input . '"),id_subregion:getValueById("input-subregion' . $attribute . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_city'])?$value['city']:$value['id_city'],
                        'id' => 'input-city'.$id_input
                    ]
                ]).'</div>';

                if (!empty($options['show_district']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[district]', ['enableClientValidation' => false])->widget(Select2::class, [
                    'data' => [$value['id_district']?:$value['district']=>$value['district']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'tags' => true,
                        'placeholder' => 'Район города',
                        'ajax' => [
                            'url' => '/address/district',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:getValueById("input-city' . $id_input . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_district'])?$value['district']:$value['id_district'],
                        'id' => 'input-district' . $id_input
                    ]
                ]).'</div>';

                if (!empty($options['show_street']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[street]')->widget(Select2::class, [
                    'data' => [$value['id_street']?:$value['street']=>$value['street']],
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Улица',
                        'tags' => true,
                        'ajax' => [
                            'url' => '/address/street',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:getValueById("input-city' . $id_input . '"),id_district:getValueById("input-district' . $attribute . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_street'])?$value['street']:$value['id_street'],
                        'id' => 'input-street' . $id_input
                    ]
                ]).'</div>';

                if (!empty($options['show_house']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[house]')->widget(Select2::class, [
                    'data' => [$value['id_house']?:$value['house']=>$value['house']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'allowClear' => true,
                        'tags' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Дом',
                        'ajax' => [
                            'url' => '/address/house',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_street:getValueById("input-street' . $id_input . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_house'])?$value['house']:$value['id_house'],
                        'id' => 'input-house' . $id_input
                    ],
                    'pluginEvents' => [
                        "select2:select" => "function(e) {
                            var regionID = $('#input-district$id_input');

                            if (regionID.length >0 && regionID.val()=='')
                            {
                                if (e.params.data.district!='')
                                {
                                    if (regionID.find('option[value=\"'+ e.params.data.id_district + '\"]').length){
                                        regionID.val(e.params.data.id_district).trigger('change');
                                    } else {
                                        var newOption = new Option(e.params.data.district, e.params.data.id_district, true, true);
                                        regionID.append(newOption).trigger('change');
                                    }
                                }
                            }
                            if ($('#latformdynamic_" . $id_input . "').length>0 && $('#latformdynamic_" . $id_input . "').val()!='')
                            {
                                $('#latformdynamic_" . $id_input . "').val(e.params.data.lat);
                            }

                            if ($('#lonformdynamic_" . $id_input . "').length>0 && $('#lonformdynamic_" . $id_input . "').val()!='')
                            {
                                $('#lonformdynamic_" . $id_input . "').val(e.params.data.lon);
                            }

                            if ($('#postcode" . $id_input . "').length>0)
                                $('#postcode" . $id_input . "').val(e.params.data.postalcode);
                        }",
                    ]
                ]).'</div>';

                if (!empty($options['show_room']))
                {
                    echo '<div class="col-md-4">';
                    echo $form->field($model, $attribute.'[room]', ['enableClientValidation' => false])->textInput(['id'=>'show_room'.$attribute,'placeholder'=>'кв.,оф.']);
                    echo '</div>';
                }

                if (!empty($options['show_postcode']))
                {
                    echo '<div class="col-md-4">';
                    echo $form->field($model, $attribute.'[postalcode]', ['enableClientValidation' => false])->textInput(['id'=>'postcode'.$id_input,'placeholder'=>'Почтовый индекс']);
                    echo '</div>';
                }

                if (!empty($options['show_coord']))
                {
                    echo '<div class="col-md-12">';
                    echo MapInputWidget::widget(['name' => $inputname.'[coords]', 'index' => $options['id'], /*'value' => $model->$clearAttribute*/]);
                    echo '</div>';
                }

                if (!empty($options['show_place']))
                echo '<div class="col-md-12">'.$form->field($model, $attribute.'[place]')->widget(Select2::class, [
                    'data' => [$value['id_place']?:$value['id_place']=>$value['place']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'tags' => false,
                        'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Место',
                        'ajax' => [
                            'url' => '/address/place',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_house:getValueById("input-house' . $id_input . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_place'])?$value['place']:$value['id_place'],
                        'id' => 'input-place' . $id_input
                    ],
                    'pluginEvents' => [
                        "select2:select" => "function(e) {

                            if ($('#input-house$id_input').val()=='')
                            {
                                selectPlace('input-place$id_input');
                            }
                        }",
                    ]
                ]).'</div>';

                echo '</div>';
                break;
            case CollectionColumn::TYPE_FILE:

                $dataOptions = [];

                if (!empty($options['acceptedFiles']))
                    $dataOptions[] = 'data-acceptedfiles="' . $options['acceptedFiles'] . '"';

                if (!empty($options['maxFiles']))
                    $dataOptions[] = 'data-maxfiles="' . $options['maxFiles'] . '"';

                if (!empty($options['filesize']))
                    $dataOptions[] = 'data-maxfilesize="' . $options['filesize'] . '"';

                $file_uploaded = '';

                $id_medias = $model->$clearAttribute;

                if (!empty($id_medias))
                {
                    if (is_string($id_medias))
                        $id_medias = json_decode($id_medias, true);
                    else
                        if (is_array($id_medias) && !empty($id_medias[0]['id']))
                        {
                            $ids = [];
                            foreach ($id_medias as $key => $data) {
                                $ids[] = $data['id'];
                            }

                            $id_medias = $ids;
                        }

                    $medias = Media::find()->where(['id_media' => $id_medias])->all();
                    foreach ($medias as $mkey => $media)
                        $file_uploaded .= $this->render('_file', ['media' => $media, 'attribute' => $attribute, 'index' => $mkey,'options'=>$options]);
                }

                if (empty($options['filesize']))
                    $options['filesize'] = 10;

                echo '
				<div data-input="' . $input->id_input . '" class="fileupload" ' . implode(' ', $dataOptions) . '>
	                <div class="fileupload_dropzone">
	                    <div class="fileupload_btn">
	                        <span class="fileupload_btn-text">Выберите файлы</span>
	                        <div class="fileupload_control"></div>
	                    </div>
	                    <div class="fileupload_content">
	                        <p class="fileupload_label">Перетащите сюда файлы для загрузки</p>
	                        <p class="text-help mt-0 mb-0">Максимальный размер файлов — <span class="maxFormSize">' . $modelForm->maxfilesize . '</span> Мб <span class="currentFormSize"></span></p>
	                    </div>
	                </div>
	                <div class="fileupload_list '.(!empty($options['pagecount'])?'show-pagecount':'').'">' . $file_uploaded . '</div>
	            </div><div class="help-block"></div>';
                break;
            case CollectionColumn::TYPE_IMAGE:
                $dataOptions = [];

                if (!empty($options['acceptedFiles']))
                    $dataOptions[] = 'data-acceptedFiles="' . $options['acceptedFiles'] . '"';

                if (!empty($options['maxFiles']))
                    $dataOptions[] = 'data-maxFiles="' . $options['maxFiles'] . '"';

                if (!empty($options['filesize']))
                    $dataOptions[] = 'data-maxFilesize="' . $options['filesize'] . '"';

                $id_medias = $model->$clearAttribute;

                $file_uploaded = '';

                if (!empty($id_medias))
                {
                    if (is_string($id_medias))
                        $id_medias = json_decode($id_medias, true);
                    else
                        if (is_array($id_medias) && !empty($id_medias[0]['id']))
                        {
                            $ids = [];
                            foreach ($id_medias as $key => $data) {
                                $ids[] = $data['id'];
                            }

                            $id_medias = $ids;
                        }

                    $medias = Media::find()->where(['id_media' => $id_medias])->all();
                    foreach ($medias as $mkey => $media)
                        $file_uploaded .= $this->render('_file', ['media' => $media, 'attribute' => $attribute, 'index' => $mkey,'options'=>$options]);
                }


                echo '
				<div data-input="' . $input->id_input . '" class="fileupload" ' . implode(' ', $dataOptions) . ' >
	                <div class="fileupload_dropzone ">
	                    <div class="fileupload_btn">
	                        <span class="fileupload_btn-text">Выберите файлы</span>
	                        <div class="fileupload_control"></div>
	                    </div>
	                    <div class="fileupload_content">
	                        <p class="fileupload_label">Перетащите сюда файлы для загрузки</p>
	                        <p class="text-help mt-0 mb-0">Максимальный размер файлов — ' . $modelForm->maxfilesize . ' Мб <span class="currentFormSize"></span></p>
	                    </div>
	                </div>
	                <div class="fileupload_list">' . $file_uploaded . '</div>
	            </div>';
                break;
            case CollectionColumn::TYPE_RADIO:

                foreach ($input->getArrayValues() as $key => $value) {
                    echo '<div class="radio-group">
								<label class="radio">
									<input data-id="'.$input->id_input.'" type="radio" name="'.$inputname.'" '.($value==$model->$clearAttribute?' checked ':'').' value="' . Html::encode($key) . '" class="radio_control">
									<span class="radio_label">' . $value . '</span>
								</label>
						  </div>';
                }
                break;
            case CollectionColumn::TYPE_CHECKBOX:

                if (!empty($options['popup']))
                    $options['data-modal'] = 'input' . $input->id_input . 'Modal';

                if (!empty($options['popup'])) {
                    echo '<div id="input' . $input->id_input . 'Modal" style="display: none; max-width: 1000px;">
								' . $options['terms'] . '
						        <p>
						        	<center>
							        	<button data-id="input' . $input->id_input . '" class="btn btn__secondary accept-checkbox">Ознакомлен</button>
							            <button data-fancybox-close="" class="btn btn-primary">Закрыть</button>
						            <center>
						        </p>
						   </div>';
                }

                $options['class'] = 'checkbox_control' . (!empty($options['popup']) ? ' modal-checkbox' : '');
                $options['value'] = (!empty($input->values) ? Html::encode($input->values) : 1);

                unset($options['popup']);
                unset($options['terms']);

                echo '<div class="checkbox-group">
					<label class="checkbox checkbox__ib">
						' . Html::checkBox($inputname, (!empty($model->$clearAttribute)), $options) . '
						<span class="checkbox_label">' . ($input->label ?? $input->name) . '</span>
					</label>
				</div>';
                break;
            case CollectionColumn::TYPE_CHECKBOXLIST:
                if (!empty($input->id_collection))
                    $current_values = (is_array($model->$clearAttribute)) ? array_keys($model->$clearAttribute) : [];
                else
                    $current_values = (is_array($model->$clearAttribute)) ? $model->$clearAttribute : [];

                echo '<div class="checkboxes">';
                foreach ($input->getArrayValues() as $key => $value) {
                    echo '
					<div class="checkbox-group">
						<label class="checkbox checkbox__ib">
							<input type="checkbox" ' . (in_array($key, $current_values) ? 'checked' : '') . ' name="'.$inputname.'[]" value="' . Html::encode($key) . '" class="checkbox_control">
							<span class="checkbox_label">' . $value . '</span>
						</label>
					</div>';
                }
                echo '</div>';
                echo '<div class="help-block"></div>';
                break;

            case CollectionColumn::TYPE_COLLECTION:

                $value = [];

                if (!empty($model->$clearAttribute))
                    $value = $model->$clearAttribute;

                if (!is_array($value))
                    $value = [$value];

                if (empty($input->search_inputs))
                    $input->search_inputs = "''";

                echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => $value,
                    'pluginOptions' => [
                        'multiple' => false,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Выберите запись',
                        'ajax' => [
                            'url' => '/collection/record-list',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term,id:' . $input->id_collection . ',id_column:' . $input->id_collection_column . ', filter:getFilter('.$input->search_inputs.',\''.$arrayGroup.'\')};}')
                        ],
                    ],
                    'options' => [
                        'value' => key($value)
                    ]
                ]);
                break;

            case CollectionColumn::TYPE_COLLECTIONS:

                $ids = $model->$clearAttribute;

                if (!empty($ids) && is_array($ids))
                    $records = CollectionRecord::find()->where(['id_record' => array_keys($ids)])->indexBy('id_record')->all();

                if (!empty($options['accept_add']))
                {
                    echo '<div class="subform-container" id="subforms' . $input->id_input . '">';

                    if (empty($records))
                        $records = [null];

                    foreach ($records as $key => $record)
                    {
                        $arrayGroup = md5(rand(0, 1000000) . time());

                        $inputs[$clearAttribute . '[]'] = $arrayGroup;

                        if (!empty($record))
                            $inputs[$attribute . '_id_record[]'] = $record->id_record;

                        echo \frontend\widgets\FormsWidget::widget([
                            'form' => $input->collection->form,
                            'arrayGroup' => $arrayGroup,
                            'collectionRecord' => $record,
                            'activeForm' => $form,
                            'inputs' => $inputs,
                            'template' => 'form_in_form',
                        ]);
                    }
                    echo '</div>';

                    echo '<div class="collections-action-buttons"><a data-id="' . $input->id_input . '" data-group="subforms' . $input->id_input . '" class="btn btn__secondary btn-primary" href="javascript:" onclick="return formCopy(this)">'.(!empty($options['button_label'])?$options['button_label']:'Добавить еще').'</a></div>';
                } else
                {
                    $value = [];

                    if (is_array($ids))
                        foreach ($ids as $id => $label)
                            if (isset($records[$id]))
                                $value[(string)$id] = $label;

                    if (!empty($options['sortable']))
                    {
$script = <<< JS
setTimeout(function(){
    var optpos = 0;
$("#{$options['id']}").next().find('.select2-selection__rendered').sortable({
      start: function(event, ui){
        optpos = ui.item.index();
      },
      stop: function(event, ui){
        var index = ui.item.index();
        console.log(optpos+' '+index);
        $("#{$options['id']} option:eq("+optpos+")").insertAfter($("#{$options['id']} option:eq("+index+")"));
      }
    }).disableSelection();},1000);
JS;
                        $this->registerJs($script, yii\web\View::POS_END);
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
                                'data' => new JsExpression('function(params) { return {q:params.term,id:' . $input->id_collection.', id_column:' . $input->id_collection_column . '};}')
                            ],
                        ],
                        'options' => [
                            'id' => $options['id'],
                            'multiple' => true,
                            'value' => array_keys($value)
                        ]
                    ]);
                }
                break;
            case CollectionColumn::TYPE_DISTRICT:

                $value = [];

                if (!empty($model->$clearAttribute)) {
                    $district = District::findOne($model->$clearAttribute);
                    if (!empty($district))
                        $value = [$district->id_district => $district->name];
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
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:$("#input-city' . $id_subform . '").val()};}')
                        ],
                    ],
                    'options' => [
                        'id' => 'input-district' . $id_subform
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
                    'options' => [
                        'id' => 'input-region' . $id_subform
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
                            'data' => new JsExpression('function(params) { return {search:params.term,id_region:$("#input-region' . $id_subform . '").val()};}')
                        ],
                    ],
                    'options' => [
                        'id' => 'input-subregion' . $id_subform
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
                            'data' => new JsExpression('function(params) { return {search:params.term,id_region:$("#input-region' . $id_subform . '").val(),id_subregion:$("#input-subregion' . $id_subform . '").val()};}')
                        ],
                    ],
                    'options' => [
                        'id' => 'input-city' . $id_subform
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
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:$("#input-city' . $id_subform . '").val(),id_district:$("#input-district' . $id_subform . '").val()};}')
                        ],
                    ],
                    'options' => [
                        'id' => 'input-street' . $id_subform
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
                            'data' => new JsExpression('function(params) { return {search:params.term,id_street:$("#input-street' . $id_subform . '").val()};}')
                        ],
                    ],
                    'pluginEvents' => [
                        "select2:select" => "function(e) {

                    		if ($('#postalcode" . $id_subform . "').length>0)
                    			$('#postalcode" . $id_subform . "').val(e.params.data.postalcode);
                    	}",
                    ]
                ]);
                break;
            case CollectionColumn::TYPE_JSON:

                $columns = json_decode($input->values, true);

                if (!is_array($columns) && !empty($columns))
                    break;

                $data = json_decode($model->$clearAttribute,true);

                if (!is_array($columns) && !empty($data))
                {
                    $columns = [];

                    foreach ($data[key($data)] as $alias => $value)
                        $columns[] = ['name'=>$alias,'alias'=>$alias];
                }
                else if (!empty($columns) && empty($data))
                    $data = [[]];

                echo $this->render('inputs/_jsontable',[
                    'model'=>$model,
                    'form'=>$form,
                    'id_input'=>$id_input,
                    'options'=>$options,
                    'columns'=>$columns,
                    'data'=>$data,
                    'input'=>$input,
                    'attribute'=>$attribute,
                    'inputname'=>$inputname,
                    'clearAttribute'=>$clearAttribute,
                ]);

                break;
            default:
                break;
        }
        ?>
        <?php if (!empty($input->hint)) { ?>
            <p class="text-help"><?= $input->hint ?></p>
        <?php } ?>
    </div>
</div>