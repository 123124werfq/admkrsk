<?php
    use yii\helpers\Html;
    use kartik\select2\Select2;

    $days = [];
    for ($i=1; $i <32 ; $i++) {
        $days[$i] = $i;
    }

    $value = $model->$clearAttribute;

    echo '<div class="flex-wrap">
                <div class="col-md-6"><label class="form-label">Дата начала</label>'.$form->field($model, $attribute.'[begin]')->textInput(['type'=>'date','max'=>date('Y-m-d',strtotime("+2 years")),'value'=> !empty($value['begin'])?date('Y-m-d', $value['begin']):'']).'</div>';
    echo '<div class="col-md-6"><label class="form-label">Дата конца</label>'.$form->field($model, $attribute.'[end]')->textInput(['type'=>'date','max'=>date('Y-m-d',strtotime("+2 years")),'value'=> !empty($value['end'])?date('Y-m-d', $value['end']):'']).'</div>';

    $repeatDisplay = '';

    if (empty($value['is_repeat']))
        $repeatDisplay = 'style="display:none"';

    $columns = [
        [
            'name'=>'Начало','alias'=>'begin','type'=>'time',
        ],
        [
            'name'=>'Конец','alias'=>'end','type'=>'time',
        ],
    ];

    $data = $value['time']??[[]];

    echo $this->render('_jsontable',[
        'model'=>$model,
        'form'=>$form,
        'id_input'=>$id_input,
        'options'=>$options,
        'columns'=>$columns,
        'data'=>$data,
        'input'=>$input,
        'attribute'=>$attribute,
        'inputname'=>$inputname.'[time]',
        'clearAttribute'=>$clearAttribute,
    ]);

    echo '<div class="col-md-6">
            <div class="checkbox-group">
                <label class="checkbox checkbox__ib">
                    ' . Html::checkBox($inputname.'[is_repeat]', (!empty($model->$clearAttribute['is_repeat'])), ['class'=>'checkbox_control repeat-switcher']) . '
                    <span class="checkbox_label">Повторяющееся событие</span>
                </label>
            </div>
          </div>';

    echo '<div class="col-md-6 is_repeat" '.$repeatDisplay.'>'.$form->field($model, $attribute.'[repeat_count]')->textInput(['type'=>'number','min'=>0,'placeholder'=>'Количество повторов']).'</div>';

    $options['item'] = function ($index, $label, $name, $checked, $value) {
        $check = $checked ? ' checked="checked"' : '';
        return '<div class="radio-group">
                    <label class="radio">
                        <input class="radio_control repeat_repeat" type="radio" name="'.$name.'" value="'.$value.'" '.$check.'/>
                        <span class="radio_label">' . $label . '</span>
                    </label>
                </div>';
    };
    $options['class'] = '';

    echo '<div class="col-md-6 is_repeat" '.$repeatDisplay.'>'.$form->field($model, $attribute.'[repeat]',['template'=>'{input}'])->radioList(['Ежедневно'=>'Ежедневно','Еженедельно'=>'Еженедельно','Ежемесячно'=>"Ежемесячно"], $options).'</div>';

    $options['class'] = 'form-control';

    echo '<div class="col-md-6 is_repeat" '.$repeatDisplay.'>';
        echo '<div class="repeat-block" '.((!empty($value['repeat']) && $value['repeat']=='Ежедневно')?'':'style="display:none"').' data-repeat="Ежедневно">';
            echo $form->field($model, $attribute.'[day_space]')->textInput(['placeholder'=>'Дней между повторами']);
        echo "</div>";

        $week = [
            'Понедельник'=>'Понедельник',
            'Вторник'=>'Вторник',
            'Среда'=>'Среда',
            'Четверг'=>'Четверг',
            'Пятница'=>'Пятница',
            'Суббота'=>'Суббота',
            'Воскресенье'=>'Воскресенье',
        ];

        $current_values = (isset($model->$clearAttribute['week'])) ? $model->$clearAttribute['week'] : [];

        echo '<div class="repeat-block" '.((!empty($value['repeat']) && $value['repeat']=='Еженедельно')?'':'style="display:none"').' data-repeat="Еженедельно">';
            echo '<div class="checkboxes">';
            foreach ($week as $key => $day)
            {
                echo '<div class="checkbox-group">
                    <label class="checkbox checkbox__ib">
                        <input type="checkbox" ' . (in_array($key, $current_values) ? 'checked' : '') . ' name="'.$inputname.'[week][]" value="' . Html::encode($key) . '" class="checkbox_control">
                        <span class="checkbox_label">' . $day . '</span>
                    </label>
                </div>';
            }
            echo '</div>';
            echo $form->field($model, $attribute.'[week_space]')->textInput(['placeholder'=>'Недель между повторами']);
        echo '</div>';


        echo '<div class="repeat-block" '.((!empty($value['repeat']) && $value['repeat']=='Ежемесячно')?'':'style="display:none"').' data-repeat="Ежемесячно">';

            $options['item'] = function ($index, $label, $name, $checked, $value) {
                $check = $checked ? ' checked="checked"' : '';
                return '<div class="radio-group">
                            <label class="radio">
                                <input class="radio_control repeat_month" type="radio" name="'.$name.'" value="'.$value.'" '.$check.'/>
                                <span class="radio_label">' . $label . '</span>
                            </label>
                        </div>';
            };
            $options['class'] = '';
            echo '<div class="col-md-6 is_repeat" '.$repeatDisplay.'>'.$form->field($model, $attribute.'[repeat_month]',['template'=>'{input}'])->radioList(['Числа месяца'=>'Числа месяца','Неделя месяца'=>'Неделя месяца'], $options).'</div>';
            $options['class'] = 'form-control';

            echo '<div class="repeat-block-month" '.((!empty($value['repeat_month']) && $value['repeat_month']=='Числа месяца')?'':'style="display:none"').' data-repeat="Числа месяца">';

                echo $form->field($model, $attribute.'[month_days]')->widget(Select2::class, [
                    'data' => $days,
                    'pluginOptions' => [
                        'multiple' => true,
                        'minimumInputLength' => 0,
                        'placeholder' => 'Дни месяца',
                    ],
                ]);
            echo '</div>';

            echo '<div class="repeat-block-month" '.((!empty($value['repeat_month']) && $value['repeat_month']=='Неделя месяца')?'':'style="display:none"').' data-repeat="Неделя месяца">';

            echo $form->field($model, $attribute.'[week_number]')->dropDownList([
                '1'=>1,
                '2'=>2,
                '3'=>3,
                '4'=>4,
                '5'=>5,
                '6'=>6]);

            echo '<div class="checkboxes">';

            $current_values = (isset($model->$clearAttribute['month_week'])) ? $model->$clearAttribute['month_week'] : [];

            foreach ($week as $key => $day)
            {
                echo '<div class="checkbox-group">
                    <label class="checkbox checkbox__ib">
                        <input type="checkbox" ' . (in_array($key, $current_values) ? 'checked' : '') . ' name="'.$inputname.'[month_week][]" value="' . Html::encode($key) . '" class="checkbox_control">
                        <span class="checkbox_label">' . $day . '</span>
                    </label>
                </div>';
            }
            echo '</div>';
            echo '</div>';
        echo '</div>';

    echo "</div>";
    echo "</div>";
?>