<?php
	use common\models\CollectionColumn;
	use yii\helpers\Html;
	use yii\helpers\ArrayHelper;

	$options = CollectionColumn::getTypeOptions($model->type);

	$columns = [];


	if (!empty($options))
	{
		echo '<div class="row-flex">';

		foreach ($options as $key => $option)
		{
			$inputOption = ['class'=>'form-control','id'=>'option'.$key];

			echo '<div class="col '.($option['type']=='richtext'?'fullwidth':'').'">
					<label for="option'.$key.'" class="control-label">'.$option['name'].'</label>';

			$value = (isset($model->options[$key]))?$model->options[$key]:'';

			switch ($option['type'])
			{
				case 'input':
					echo Html::textInput("FormInput[options][$key]",$value,$inputOption);
					break;
				case 'checkbox':
					if ($model->isNewRecord)
						$value = 1;

					echo Html::hiddenInput("FormInput[options][$key]",'');
					echo Html::checkBox("FormInput[options][$key]",$value,['style'=>'margin-left:10px;','id'=>'option'.$key]);
					break;
				case 'column':
					if (empty($columns))
					{
						$columns = $model->form->collection->getColumns()->select(['id_column','name'])->where(['type'=>[CollectionColumn::TYPE_DATE,CollectionColumn::TYPE_DATETIME]])->all();
						$columns = ArrayHelper::map($columns,'id_column','name');
					}

					echo Html::dropDownList("FormInput[options][$key]",$value,$columns,$inputOption);
					break;
				case 'richtext':
					$inputOption['class'] .= ' redactor';
					echo Html::textArea("FormInput[options][$key]",$value,$inputOption);
					echo '<script type="text/javascript">tinymce.init(tinymceConfig);</script>';
					break;
				case 'dropdown':
					if (!empty($option['is_relation']))
					{
						$values = [];

						if (!empty($model->collection))
						{
							$columns = $model->collection->getColumns()
										->joinWith('input as input')
											->where(['input.id_collection'=>$model->column->id_collection])
											->select(['input.id_column','input.name','db_collection_column.id_column'])
											->asArray()->all();

							foreach ($columns as $ckey => $data) {
								$values[$data['id_column']] = $data['name'];
							}
						}

						$inputOption['prompt'] = "Выберите колонку";
					}
					else
						$values = $option['values'];

					echo Html::dropDownList("FormInput[options][$key]",$value,$values,$inputOption);
					break;
				default:
					break;
			}
			echo '</div>';
		}
		echo '</div>';
	}
?>