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

if (!empty($input->required))
    $options['required'] = true;

if (!empty($input->readonly))
    $options['readonly'] = true;

if (empty($options['id']))
    $options['id'] = "input" . $input->id_input;

$groupClass = '';

if ($input->type == CollectionColumn::TYPE_CHECKBOXLIST)
    $groupClass .= ' checkboxlist';

if ($input->type == CollectionColumn::TYPE_SELECT)
    $groupClass .= ' custom-select';

$clearAttribute = $attribute = "input$input->id_input";

if (!empty($arrayGroup))
    $attribute = "[$arrayGroup]" . $attribute;

$id_subform = (!empty($subform)) ? $subform->id_form : '';
?>

<div id="element<?= $element->id_element ?>"
     class="col" <?= (!empty($styles)) ? 'style="' . implode(';', $styles) . '"' : '' ?>>
    <div id="inputGroup<?= $input->id_input ?>" class="form-group <?= $groupClass ?>">
        <?php if (!empty($input->copyInput)){?>
            <div class="checkbox-group">
                <label class="checkbox checkbox__ib">
                    <input class="checkbox_control copydate" type="checkbox" data-input="<?=$input->copyInput->id_input?>" name="copydate" value="<?=$input->id_input?>"/>
                    <span class="checkbox_label">Совпадает с <?=$input->copyInput->label?$input->copyInput->label:$input->copyInput->name?></span>
                </label>
            </div>
        <?php }?>

        <?php if (!empty($input->label) && $input->type != CollectionColumn::TYPE_CHECKBOX) { ?>
            <label class="form-label"><?= $input->label ?><?= !empty($options['required']) ? ' <span class="red">*</span>' : '' ?></label>
        <?php } ?>
        <?php switch ($input->type) {
            case CollectionColumn::TYPE_SERVICETARGET:
                echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(), $options);
                break;
            case CollectionColumn::TYPE_SERVICE:
                echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(), $options);
                break;
            case CollectionColumn::TYPE_SELECT:
                echo $form->field($model, $attribute)->dropDownList($input->getArrayValues(), $options);
                break;
            case CollectionColumn::TYPE_MAP:
                echo MapInputWidget::widget(['name' => 'FormDynamic[' . $attribute . ']', 'index' => $options['id'], 'value' => $model->$clearAttribute]);
                break;
            case CollectionColumn::TYPE_DATE:
                $options['type'] = 'date';

                if (!is_numeric($model->$clearAttribute) && (!empty($model->$clearAttribute)))
                    $model->$clearAttribute = strtotime($model->$clearAttribute);

                if (!empty($model->$clearAttribute))
                    $model->$clearAttribute = date('Y-m-d', $model->$clearAttribute);

                echo $form->field($model, $attribute)->textInput($options);
                break;
            case CollectionColumn::TYPE_DATETIME:

                if (!is_numeric($model->$clearAttribute) && (!empty($model->$clearAttribute)))
                    $model->$clearAttribute = strtotime($model->$clearAttribute);

                if (!empty($model->$clearAttribute))
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
            case CollectionColumn::TYPE_RICHTEXT:
                $options['class'] .= ' redactor';
                echo $form->field($model, $attribute)->textArea($options);
                break;
            case CollectionColumn::TYPE_REPEAT:
                echo '<div class="checkbox-group">
                            <label class="checkbox checkbox__ib">
                                ' . Html::checkBox('FormDynamic[' . $attribute . '][active]', (!empty($model->$clearAttribute)), ['class'=>'checkbox_control']) . '
                                <span class="checkbox_label">' . ($input->label ?? $input->name) . '</span>
                            </label>
                      </div>';
                echo $form->field($model, $attribute.'[repeat]')->radioList([1=>'Ежедневно',7=>'Еженедельно',31=>"Ежемесячно"], $options);
                echo $form->field($model, $attribute.'[days]')->textinput(['placeholder'=>'Дней между повторами']);
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

                if (empty($model->$attribute))
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
                        'postalcode'=>''
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

                    $model->$attribute = $value;
                }
                else
                    $value = $model->$attribute;

                echo '<div class="flex-wrap">';

                if (!empty($options['show_country']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[country]')->widget(Select2::class, [
                    'data' => [$value['id_country']=>$value['country']],
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
                        'id' => 'input-country' . $attribute
                    ]
                ]).'</div>';

                if (!empty($options['show_region']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[region]')->widget(Select2::class, [
                    'data' => [$value['id_region']=>$value['region']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'minimumInputLength' => 0,
                        'tags'=> true,
                        'placeholder' => 'Регион',
                        'ajax' => [
                            'url' => '/address/region',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_country:getValueById("input-country' . $attribute . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_region'])?$value['region']:$value['id_region'],
                        'id' => 'input-region' . $attribute
                    ]
                ]).'</div>';

                if (!empty($options['show_subregion']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[subregion]')->widget(Select2::class, [
                    'data' => [$value['id_subregion']=>$value['subregion']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'minimumInputLength' => 0,
                        'tags'=> true,
                        'placeholder' => 'Область / Район',
                        'ajax' => [
                            'url' => '/address/subregion',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_region:getValueById("input-region' . $attribute . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_subregion'])?$value['subregion']:$value['id_subregion'],
                        'id' => 'input-subregion' . $attribute
                    ]
                ]).'</div>';

                if (!empty($options['show_city']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[city]')->widget(Select2::class, [
                    'data' => [$value['id_city']=>$value['city']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'minimumInputLength' => 0,
                        'tags' => true,
                        'placeholder' => 'Город',
                        'ajax' => [
                            'url' => '/address/city',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_region:getValueById("input-region' . $attribute . '"),id_subregion:getValueById("input-subregion' . $attribute . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_city'])?$value['city']:$value['id_city'],
                        'id' => 'input-city'.$attribute
                    ]
                ]).'</div>';

                if (!empty($options['show_district']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[district]')->widget(Select2::class, [
                    'data' => [$value['id_district']=>$value['district']],
                    'pluginOptions' => [
                        'multiple' => false,
                        'tags' => true,
                        'placeholder' => 'Район города',
                        'ajax' => [
                            'url' => '/address/district',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:getValueById("input-city' . $attribute . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_district'])?$value['district']:$value['id_district'],
                        'id' => 'input-district' . $attribute
                    ]
                ]).'</div>';

                //echo '<div class="col-md-12"></div>';

                if (!empty($options['show_street']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[street]')->widget(Select2::class, [
                    'data' => [$value['id_street']=>$value['street']],
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Улица',
                        'tags' => true,
                        'ajax' => [
                            'url' => '/address/street',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_city:getValueById("input-city' . $attribute . '"),id_district:getValueById("input-district' . $attribute . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_street'])?$value['street']:$value['id_street'],
                        'id' => 'input-street' . $attribute
                    ]
                ]).'</div>';

                if (!empty($options['show_house']))
                echo '<div class="col-md-4">'.$form->field($model, $attribute.'[house]')->widget(Select2::class, [
                    'data' => [$value['id_house']=>$value['house']],
                    'pluginOptions' => [
                        'multiple' => false,
                        //'allowClear' => true,
                        'tags' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Дом',
                        'ajax' => [
                            'url' => '/address/house',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {search:params.term,id_street:getValueById("input-street' . $attribute . '")};}')
                        ],
                    ],
                    'options' => [
                        'value'=>empty($value['id_house'])?$value['house']:$value['id_house'],
                        'id' => 'input-house' . $attribute
                    ],
                    'pluginEvents' => [
                        "select2:select" => "function(e) {
                            if ($('#postcode" . $attribute . "').length>0)
                                $('#postcode" . $attribute . "').val(e.params.data.postalcode);
                        }",
                    ]
                ]).'</div>';


                if (!empty($options['show_room']))
                    echo $form->field($model, $attribute.'[room]')->textInput(['id'=>'show_room'.$attribute,'placeholder'=>'кв.,оф.']);

                //echo '<div class="col-md-4">';

                //echo '<div class="col-md-12"></div>';

                if (!empty($options['show_postcode']))
                echo $form->field($model, $attribute.'[postalcode]')->textInput(['id'=>'postcode'.$attribute,'placeholder'=>'Почтовый индекс']);
                //echo '</div>';

                if (!empty($options['show_coords']))
                {
                    echo '<div class="col-md-12">';
                    echo MapInputWidget::widget(['name' => 'FormDynamic[' . $attribute . '][coords]', 'index' => $options['id'], /*'value' => $model->$clearAttribute*/]);
                    echo '</div>';
                }
                echo '</div>';

                /*$script = <<< JS
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
                $this->registerJs($script, yii\web\View::POS_END);*/
                break;
            case CollectionColumn::TYPE_FILE:

                $dataOptions = [];

                if (!empty($options['acceptedFiles']))
                    $dataOptions[] = 'data-acceptedfiles="' . $options['acceptedFiles'] . '"';

                if (!empty($options['maxFiles']))
                    $dataOptions[] = 'data-maxfiles="' . $options['maxFiles'] . '"';

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

                echo '
				<div data-input="' . $input->id_input . '" class="fileupload" ' . implode(' ', $dataOptions) . '>
	                <div class="fileupload_dropzone">
	                    <div class="fileupload_btn">
	                        <span class="fileupload_btn-text">Выберите файлы</span>
	                        <div class="fileupload_control"></div>
	                    </div>
	                    <div class="fileupload_content">
	                        <p class="fileupload_label">Перетащите сюда файлы для загрузки</p>
	                        <p class="text-help mt-0 mb-0">Максимальный размер файлов — ' . (!empty($options['filesize']) ? $options['filesize'] : '10') . ' Мб</p>
	                    </div>
	                </div>
	                <div class="fileupload_list '.(!empty($options['pagecount'])?'show-pagecount':'').'">' . $file_uploaded . '</div>
	            </div>';
                break;
            case CollectionColumn::TYPE_IMAGE:
                $dataOptions = [];

                if (!empty($options['acceptedFiles']))
                    $dataOptions[] = 'data-acceptedFiles="' . $options['acceptedFiles'] . '"';

                if (!empty($options['maxFiles']))
                    $dataOptions[] = 'data-maxFiles="' . $options['maxFiles'] . '"';

                $id_medias = $model->$clearAttribute;

                $file_uploaded = '';

                if (!empty($id_medias)) {
                    if (is_string($id_medias))
                        $id_medias = json_decode($id_medias, true);

                    $medias = Media::find()->where(['id_media' => $id_medias])->all();

                    foreach ($medias as $mkey => $media)
                        $file_uploaded .= $this->render('_file', ['media' => $media, 'attribute' => $attribute, 'index' => $mkey]);
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
	                        <p class="text-help mt-0 mb-0">Максимальный размер файлов — ' . (!empty($options['filesize']) ? $options['filesize'] : '10') . ' Мб</p>
	                    </div>
	                </div>
	                <div class="fileupload_list">' . $file_uploaded . '</div>
	            </div>';
                break;
            case CollectionColumn::TYPE_RADIO:
                foreach ($input->getArrayValues() as $key => $value) {
                    echo '<div class="radio-group">
								<label class="radio">
									<input type="radio" name="FormDynamic[' . $attribute . ']" value="' . Html::encode($key) . '" class="radio_control">
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
						' . Html::checkBox('FormDynamic[' . $attribute . ']', (!empty($model->$clearAttribute)), $options) . '
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
							<input type="checkbox" ' . (in_array($key, $current_values) ? 'checked' : '') . ' name="FormDynamic[' . $attribute . '][]" value="' . Html::encode($key) . '" class="checkbox_control">
							<span class="checkbox_label">' . $value . '</span>
						</label>
					</div>';
                }
                echo '</div>';
                break;
            case CollectionColumn::TYPE_COLLECTION:

                $value = [];

                if (!empty($model->$attribute))
                    $value = $model->$attribute;

                echo $form->field($model, $attribute)->widget(Select2::class, [
                    'data' => $value,
                    'pluginOptions' => [
                        'multiple' => false,
                        'minimumInputLength' => 1,
                        'placeholder' => 'Выберите запись',
                        'ajax' => [
                            'url' => '/collection/record-list',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term,id:' . $input->id_collection . ',id_column:' . $input->id_collection_column . '};}')
                        ],
                    ],
                    'options' => [
                        'value' => key($value)
                    ]
                ]);
                break;

            case CollectionColumn::TYPE_COLLECTIONS:

                $ids = $model->$clearAttribute;

                $records = [];

                if (!empty($ids))
                    $records = CollectionRecord::find()->where(['id_record' => array_keys($ids)])->indexBy('id_record')->all();

                if (!empty($options['accept_add']))
                {
                    echo '<div id="subforms' . $input->id_input . '">';

                    if (empty($records))
                        $records = [null];

                    foreach ($records as $key => $record) {
                        $arrayGroup = md5(rand(0, 10000) . time());

                        $inputs[$attribute . '[]'] = $arrayGroup;

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

                    echo '<div class="collections-action-buttons"><a data-id="' . $input->id_input . '" data-group="subforms' . $input->id_input . '" class="btn btn__secondary form-copy" href="javascript:">Добавить еще</a></div>';
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
                                'data' => new JsExpression('function(params) { return {q:params.term,id:' . $input->id_collection . '};}')
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

                if (!empty($model->$attribute)) {
                    $district = District::findOne($model->$attribute);
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

                $data = json_decode($model->$attribute,true);

                if (!is_array($columns) && !empty($data))
                {
                    $columns = [];

                    foreach ($data[key($data)] as $alias => $value)
                        $columns[] = ['name'=>$alias,'alias'=>$alias];
                }
                else if (!empty($columns) && empty($data))
                    $data = [[]];

            ?>
                <table class="form-table">
                    <thead>
                    <tr>
                        <?php foreach ($columns as $key => $column) {
                            echo '<th ' . (!empty($column['width']) ? 'style="width:' . $column['width'] . '%"' : '') . ' >' . $column['name'] . '</th>';
                        } ?>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="inputs<?= $input->id_input ?>">
                        <?php
                        /*if (empty($data))
                        {
                            $i = 0;
                            foreach ($columns as $key => $column)
                            {
                                echo '<td><input id="' . $attribute . '_col' . $i . '" type="' . ($column['type'] ?? 'text') . '" name="FormDynamic[input' . $input->id_input . '][0][' . $i . ']" class="form-control"/></td>';
                                $i++;
                            }
                        }
                        else
                        {*/
                            foreach ($data as $rkey => $row)
                            {
                                echo "<tr>";
                                $i = 0;
                                foreach ($columns as $ckey => $column)
                                {
                                    $alias = $column['alias'];

                                    if (!empty($column['type']) && $column['type']=='list')
                                    {
                                        $values = [];

                                        foreach ((!empty($column['values']))?explode(';', $column['values']):[] as $vkey => $value)
                                            $values[$value] = $value;

                                        echo '<td '.(!empty($column['width'])?'width="'.$column['width'].'"':'').'>'.Html::dropDownList('FormDynamic['.$attribute.']['.$rkey.']['.$alias.']',$row[$alias]??'',$values,['id'=>'input'.$input->id_input.'_col','class'=>"form-control"]).'</td>';
                                    }
                                    else
                                        echo '<td '.(!empty($column['width'])?'width="'.$column['width'].'"':'').'>'.Html::textINput('FormDynamic['.$attribute.']['.$rkey.']['.$alias.']',$row[$alias]??'',['id'=>'input'.$input->id_input.'_col','class'=>"form-control"]).'</td>';
                                    $i++;
                                }
                                echo '<td width="10" class="td-close">
                                            <a class="close" onclick="return removeRow(this)" href="javascript:">&times;</a>
                                      </td>
                                      </tr>';
                            }
                        //}
                        ?>
                    </tbody>
                    <tfoot>
                    <td colspan="<?= count($columns) + 1 ?>">
                        <a onclick="addInput('inputs<?= $input->id_input ?>')" class="btn btn__gray" href="javascript:">Добавить</a>
                    </td>
                    </tfoot>
                </table>
                <?php
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