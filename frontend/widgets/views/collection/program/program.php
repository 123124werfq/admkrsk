<?php
    use yii\helpers\Html;

    $this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third">
                <h1 class="searchable"><?=Html::encode($collection->name)?></h1>
            </div>
            <div class="col-third">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
        <br/>
        <div class="header-controls">
            <form id="program-filter" action="" method="get">
                <div class="btn-group">
                  <?php if (!empty($search_columns)){?>
                    <?php foreach ($search_columns as $key => $column)
                    {
                        $name = 'search_column['.$unique_hash.']['.$column['column']->id_column.']';
                        if ($column['type']==0)
                        {
                            echo '<div class="btn-group_item">
                                    <div class="custom-select custom-select__placeholder custom-select__inline ui-front">';
                            echo Html::dropDownList($name,'',$column['values'],['class'=>'','prompt'=>$column['column']->name]);
                            echo '</div>';
                            echo '</div>';
                        }
                        elseif ($column['type']==3)
                            echo '<div class="btn-group_item">
                                        <div class="datepicker-holder">
                                            <input type="text" name="'.$name.'" class="form-control form-control_datepicker mb-sm-all-0 datepicker-ajax" placeholder="'.Yii::t('site', 'Показать за период').'" autocomplete="off">
                                            <button class="form-control-reset material-icons" type="button">clear</button>
                                        </div>
                                    </div>';
                        else
                            echo Html::textInput($name,'',['class'=>'form-control','placeholder'=>$column['column']->name,'max-lenght'=>255]);
                    }?>
                  <?php }?>
                </div>
            </form>
        </div>
        <div class="program-list">
            <?=$this->render('_program-list',['groups'=>$groups,'columns'=>$columns])?>
        </div>
    </div>
</div>